<?php

namespace App\Tests\Controller;

use App\Security\SamlUser;
use App\Tests\AppDatabaseTestTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminExportConfigTest extends WebTestCase
{
    use AppDatabaseTestTrait;

    private function setUpUser(): SamlUser
    {
        $user = new SamlUser('WEBID99999990');
        $user->setSamlAttributes([
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress' => ['honeybear@middlebury.edu'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname' => ['Winnie'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname' => ['The-Pooh'],
            'AssignedRoles' => ['App.EmailSendAllowed', 'App.Manager'],
        ]);

        return $user;
    }

    /**
     * Create an export config an return its ID.
     *
     * @param KernelBrowser $client
     *                              The client for making requests
     *
     * @return int
     *             The export ID
     */
    private function createExportConfig(KernelBrowser $client): int
    {
        // Get the form.
        $crawler = $client->request('GET', '/admin/exports/create');
        $this->assertResponseIsSuccessful();
        // Submit the form.
        $form = $crawler->filter('#config-create-form')->form();
        $form['label'] = 'Fall/Spring Catalog';
        $form['catalog_id'] = 'catalog.MCUG';
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Get the ID from resulting modification form.
        return $crawler->filter('#configId')->attr('value');
    }

    public function testConfigModifyVisibility(): void
    {
        $client = static::createClient();
        $client->loginUser($this->setUpUser());

        $crawler = $client->request('GET', '/admin/exports/config');
        $this->assertResponseIsSuccessful();
    }

    public function testConfigModifyVisibilityNonManager(): void
    {
        $client = static::createClient();

        $user = new SamlUser('WEBID99999990');
        $user->setSamlAttributes([
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress' => ['honeybear@middlebury.edu'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname' => ['Winnie'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname' => ['The-Pooh'],
            'AssignedRoles' => ['App.EmailSendAllowed'],
        ]);
        $client->loginUser($user);

        $client->request('GET', '/admin/exports/config');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testConfigCreateAndDelete(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->loginUser($this->setUpUser());

        // Get the form.
        $crawler = $client->request('GET', '/admin/exports/create');
        $this->assertResponseIsSuccessful();

        // Submit the form.
        $form = $crawler->filter('#config-create-form')->form();
        $form['label'] = 'Fall/Spring Catalog';
        $form['catalog_id'] = 'catalog.MCUG';
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Delete the config we just created.
        $deleteUrl = $crawler->filter('#config-body')->attr('data-delete-url');
        $csrfKey = $crawler->filter('#csrf-key-config-modify');
        $crawler = $client->request('POST', $deleteUrl, [
            $csrfKey->attr('name') => $csrfKey->attr('value'),
        ]);
        $this->assertResponseIsSuccessful();
    }

    public function testConfigModifyAndRevisions(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->loginUser($this->setUpUser());

        $exportId = $this->createExportConfig($client);

        // Get the modification form.
        $crawler = $client->request('GET', '/admin/exports/config/'.$exportId);
        $this->assertResponseIsSuccessful();
        $csrfKey = $crawler->filter('#csrf-key-config-modify');
        $saveUrl = $crawler->filter('#config-body')->attr('data-insert-revision-url');

        // Save a new revision.
        $crawler = $client->request('POST', $saveUrl, [
            $csrfKey->attr('name') => $csrfKey->attr('value'),
            'note' => 'This is the first revision.',
            'jsonData' => '{"group1":{"title":"Geology","section1":{"type":"h1","value":"Geology+and+Earth+Science;Geology"},"section2":{"type":"course_list","value":"topic.subject.GEOL"}}}',
        ]);
        $this->assertResponseIsSuccessful();

        // Save another new revision.
        $crawler = $client->request('POST', $saveUrl, [
            $csrfKey->attr('name') => $csrfKey->attr('value'),
            'note' => 'This is the second revision.',
            'jsonData' => '{"group1":{"title":"Geology","section1":{"type":"h1","value":"Geology+and+Earth+Science;Geology"},"section2":{"type":"course_list","value":"topic.subject.GEOL"}},"group2":{"title":"Physics","section1":{"type":"h1","value":"Physics"},"section2":{"type":"custom_text","value":"About+Physics..."},"section3":{"type":"course_list","value":"topic.subject.PHYS"}}}',
        ]);
        $this->assertResponseIsSuccessful();

        // Load the revision history page.
        $crawler = $client->request('GET', '/admin/exports/'.$exportId.'/revisions');
        $this->assertResponseIsSuccessful();
        $revisionIds = [];
        // Get the revision ids.
        foreach ($crawler->filter('.revId') as $revIdInput) {
            $revisionIds[] = $revIdInput->getAttribute('value');
        }
        $this->assertGreaterThan(1, count($revisionIds));
        // Get the CSRF key and revert URL.
        $csrfKey = $crawler->filter('#csrf-key-config-revert');
        foreach ($crawler->filter('.revert-button') as $revertButton) {
            $revisionId = $revertButton->getAttribute('data-rev-id');
            $revertUrl = $revertButton->getAttribute('data-url');
        }

        // Try loading the compare revisions page.
        $crawler = $client->request('GET', '/admin/exports/'.$exportId.'/revisiondiff/'.$revisionIds[0].'/'.$revisionIds[1]);
        $this->assertResponseIsSuccessful();

        // Try loading the JSON view page.
        $crawler = $client->request('GET', '/admin/exports/'.$exportId.'/revision/'.$revisionIds[0].'/json');
        $this->assertResponseIsSuccessful();

        // Try reverting to the first revision.
        $client->request('POST', $revertUrl, [
            $csrfKey->attr('name') => $csrfKey->attr('value'),
            'revId' => $revisionId,
        ]);
        $this->assertResponseIsSuccessful();

        // Try loading the latest JSON file.
        $client->request('GET', '/admin/exports/'.$exportId.'/latest.json');
        $this->assertResponseIsSuccessful();
        $jsonString = $client->getResponse()->getContent();
        $result = json_decode($jsonString, \JSON_THROW_ON_ERROR);
        $this->assertIsArray($result);
    }

    public function testGenerateCourseList(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->loginUser($this->setUpUser());

        // Try loading the latest JSON file.
        $crawler = $client->request('GET', '/admin/exports/generatecourselist/catalog.MCUG');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.section-dropdown', 'Chemistry');
    }
}
