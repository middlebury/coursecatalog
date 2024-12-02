<?php

namespace App\Tests\Controller;

use App\Security\SamlUser;
use App\Tests\AppDatabaseTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminTest extends WebTestCase
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

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->loginUser($this->setUpUser());

        $crawler = $client->request('GET', '/admin');

        $this->assertResponseIsSuccessful();
    }

    public function testIndexAnonymous(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin');

        $this->assertResponseRedirects();
    }

    public function testIndexNonManager(): void
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

        $client->request('GET', '/admin');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testTermVisibilityList(): void
    {
        $client = static::createClient();
        $client->loginUser($this->setUpUser());

        $crawler = $client->request('GET', '/admin/terms');
        $this->assertResponseIsSuccessful();

        $crawler = $client->request('GET', '/admin/terms?catalog=MCUG');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.section_admin', '200990');
    }
}
