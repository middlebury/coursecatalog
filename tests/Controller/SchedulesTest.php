<?php

namespace App\Tests\Controller;

use App\Security\SamlUser;
use App\Tests\AppDatabaseTestTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SchedulesTest extends WebTestCase
{
    use AppDatabaseTestTrait;

    private function setUpClient(): KernelBrowser
    {
        $this->user = new SamlUser('WEBID99999990');
        $this->user->setSamlAttributes([
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress' => ['honeybear@middlebury.edu'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname' => ['Winnie'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname' => ['The-Pooh'],
        ]);

        // Bookmark some courses so they are available for schedule building.
        $client = static::createClient();
        $client->loginUser($this->user);
        $client->request('GET', '/bookmarks/add/course.CHEM0104');
        $client->request('GET', '/bookmarks/add/course.PHYS0201');
        $client->followRedirects();

        return $client;
    }

    private function createSchedule(KernelBrowser $client, $name): int
    {
        $crawler = $client->request('GET', '/schedules/list/catalog.MCUG/term.200990');
        $crawler = $client->submitForm('Create new schedule');
        $this->assertResponseIsSuccessful();
        // Find the greatest Schedule Id after creating the new one.
        $scheduleId = 0;
        foreach ($crawler->filter('div.schedule') as $scheduleDiv) {
            $id = (int) str_replace('schedule_', '', $scheduleDiv->getAttribute('id'));
            if ($id > $scheduleId) {
                $scheduleId = $id;
            }
        }
        if (0 === $scheduleId) {
            throw new \Exception('Unable to find the last schedule id.');
        }

        // Update the name.
        $updateNameButton = $crawler->selectButton('submit_update_schedule_'.$scheduleId);
        $form = $updateNameButton->form();
        $form['name'] = $name;
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();
        $this->assertEquals($crawler->filter("#update_schedule_$scheduleId input[name='name']")->attr('value'), $name);

        return $scheduleId;
    }

    private function addSectionsToSchedule(KernelBrowser $client, int $scheduleId, array $sectionIds)
    {
        $crawler = $client->request('GET', '/schedules/list/catalog.MCUG/term.200990');

        // Add a section to the schedule.
        $csrfKey = $crawler->filter('.add_section_form input[name="csrf_key"]')->attr('value');
        $args = [
            'csrf_key' => $csrfKey,
            'scheduleId' => $scheduleId,
        ];
        if (count($sectionIds) > 1) {
            $args['section_set'] = 'link_set.1';
        } else {
            $args['section_set'] = 'link_set.NULL';
        }
        foreach ($sectionIds as $i => $sectionId) {
            $args['section_group_'.$i] = $sectionId;
        }
        $crawler = $client->request('POST',
            '/schedules/add/catalog.MCUG/term.200990',
            $args,
        );
        $this->assertResponseIsSuccessful();

        return $crawler;
    }

    public function testListCreateRenameAddView(): void
    {
        $client = $this->setUpClient();
        $scheduleId = $this->createSchedule($client, 'Science Forward');

        // Fetch a list of sections to add to the schedule.
        $client->request('GET', "/schedules/sectionsforcourse/course.CHEM0104/term.200990?scheduleId=$scheduleId");
        $this->assertResponseIsSuccessful();
        $jsonString = $client->getResponse()->getContent();
        $this->assertStringContainsString('section.200990.90036', $jsonString);
        $this->assertStringContainsString('section.200990.90041', $jsonString);
        $this->assertStringContainsString('section.200990.90045', $jsonString);

        // Add a section to the schedule.
        $crawler = $this->addSectionsToSchedule($client, $scheduleId,
            [
                'section.200990.90036', // CHEM0104 A - Lecture.
                'section.200990.90041', // CHEM0104 T - Discussion.
                'section.200990.90045', // CHEM0104 W - Lab.
            ]
        );
        // Make sure we have our sections listed in the schedule.
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offerings .offering .offering_name a:contains('CHEM0104A-F09')")->count());
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offerings .offering .offering_name a:contains('CHEM0104T-F09')")->count());
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offerings .offering .offering_name a:contains('CHEM0104W-F09')")->count());

        // Ensure that we can load the image of the schedule.
        $client->request('GET', "/schedules/png/$scheduleId.png");
        $this->assertResponseIsSuccessful();
        $this->assertEquals($client->getResponse()->headers->get('Content-Type'), 'image/png');
    }

    public function testPrintView()
    {
        $client = $this->setUpClient();
        $scheduleId = $this->createSchedule($client, 'Print Test');
        // Add a section to the schedule.
        $crawler = $this->addSectionsToSchedule($client, $scheduleId,
            [
                'section.200990.90036', // CHEM0104 A - Lecture.
                'section.200990.90041', // CHEM0104 T - Discussion.
                'section.200990.90045', // CHEM0104 W - Lab.
            ]
        );

        // Ensure that we can load the print view of the schedule.
        $client->request('GET', "/schedules/print/$scheduleId");
        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('CHEM0104A-F09')")->count());
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('CHEM0104T-F09')")->count());
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('CHEM0104W-F09')")->count());

        // Ensure that we can load the JSON list of events for the schedule.
        $client->request('GET', "/schedules/eventsjson/$scheduleId.json");
        $this->assertResponseIsSuccessful();
        $jsonString = $client->getResponse()->getContent();
        $this->assertStringContainsString('section.200990.90036', $jsonString);
        $this->assertStringContainsString('section.200990.90041', $jsonString);
        $this->assertStringContainsString('section.200990.90045', $jsonString);
    }

    public function testRemoveSection()
    {
        $client = $this->setUpClient();
        $scheduleId = $this->createSchedule($client, 'Remove Test');
        // Add a section to the schedule.
        $crawler = $this->addSectionsToSchedule($client, $scheduleId,
            [
                'section.200990.90036', // CHEM0104 A - Lecture.
                'section.200990.90041', // CHEM0104 T - Discussion.
                'section.200990.90045', // CHEM0104 W - Lab.
            ]
        );
        $crawler = $this->addSectionsToSchedule($client, $scheduleId,
            [
                'section.200990.90125', // PHYS0201 A - Lecture.
            ]
        );

        // We should have sections from both courses.
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('CHEM0104A-F09')")->count());
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('CHEM0104T-F09')")->count());
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('CHEM0104W-F09')")->count());
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('PHYS0201A-F09')")->count());
        // print $crawler->outerHtml();

        // Remove the Chemistry sections.
        $form = $crawler->filter("#remove_section_form_{$scheduleId}_section_200990_90036")->form();
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();

        // We should now only have sections from PHYS.
        $this->assertGreaterThan(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('PHYS0201A-F09')")->count());
        $this->assertEquals(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('CHEM0104A-F09')")->count());
        $this->assertEquals(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('CHEM0104T-F09')")->count());
        $this->assertEquals(0, $crawler->filter("#schedule_$scheduleId .offering .offering_name a:contains('CHEM0104W-F09')")->count());
    }

    public function testEmail()
    {
        $client = $this->setUpClient();
        $scheduleId = $this->createSchedule($client, 'Email Test');
        // Add a section to the schedule.
        $crawler = $this->addSectionsToSchedule($client, $scheduleId,
            [
                'section.200990.90036', // CHEM0104 A - Lecture.
                'section.200990.90041', // CHEM0104 T - Discussion.
                'section.200990.90045', // CHEM0104 W - Lab.
            ]
        );

        // print $crawler->outerHtml();

        $form = $crawler->filter("#send_email_form_$scheduleId")->form();
        $crawler = $client->submit($form, [
            'to' => 'person1@example.edu, person2@example.org',
        ]);
        $this->assertResponseIsSuccessful();
    }
}
