<?php

namespace App\Tests\Controller;

use App\Security\SamlUser;
use App\Tests\AppDatabaseTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminAntirequisitesTest extends WebTestCase
{
    use AppDatabaseTestTrait;

    protected function setUp(): void
    {
        // Make sure that when we call static::createClient() that we don't
        // get a logic exception from other tests doing something.
        self::ensureKernelShutdown();
    }

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

    public function testAntirequisiteList(): void
    {
        $client = static::createClient();
        $client->loginUser($this->setUpUser());

        $crawler = $client->request('GET', '/admin/antirequisites');
        $this->assertResponseIsSuccessful();
    }

    public function testAntirequisiteListNonManager(): void
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

        $client->request('GET', '/admin/antirequisites');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAntirequisiteAddListDelete(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->loginUser($this->setUpUser());

        $crawler = $client->request('GET', '/admin/antirequisites');
        $this->assertResponseIsSuccessful();

        // Search for an antirequisite to add.
        $form = $crawler->filter('#search-antirequisites-form')->form();
        $form['search_subj_code'] = 'GEOG';
        $form['search_crse_numb'] = '0250';
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Check the first match and add it.
        $form = $crawler->filter('#add-antirequisites-form')->form();
        $form['equivalents_to_add'][0]->tick();
        $first = $form['equivalents_to_add'][0]->getValue();
        $form[$first.'-comments'] = 'This is a test equivalency.';
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Make sure that it is in our list now.
        $this->assertSelectorTextContains('.section_admin .subj_code', 'GEOG');

        // Delete this antirequisite.
        // print $crawler->outerHtml();
        $form = $crawler->filter('.delete-antirequisite-form')->form();
        $crawler = $client->submit($form);
        $this->assertResponseIsSuccessful();

        // Confirm that the antirequisite is gone.
        $this->assertSelectorNotExists('.section_admin .subj_code');
    }
}
