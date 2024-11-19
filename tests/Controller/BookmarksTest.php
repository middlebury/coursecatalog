<?php

namespace App\Tests\Controller;

use App\Security\SamlUser;
use App\Tests\AppDatabaseTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookmarksTest extends WebTestCase
{
    use AppDatabaseTestTrait;

    protected function setUp(): void
    {
        $this->user = new SamlUser('WEBID99999990');
        $this->user->setSamlAttributes([
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress' => ['honeybear@milne.example.com'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname' => ['Winnie'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname' => ['The-Pooh'],
        ]);
    }

    public function testAdd(): void
    {
        $client = static::createClient();
        $client->loginUser($this->user);
        $crawler = $client->request('GET', '/bookmarks/add/course.PHYS0201');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount(1, 'response success');

        $crawler = $client->request('GET', '/bookmarks/add/course.PHYS0201');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount(1, 'response error');
        $this->assertSelectorTextSame('response error', 'Bookmark already added.');
    }

    public function testRemove(): void
    {
        $client = static::createClient();
        $client->loginUser($this->user);
        $crawler = $client->request('GET', '/bookmarks/add/course.CHEM0104');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount(1, 'response success');

        $crawler = $client->request('GET', '/bookmarks/remove/course.CHEM0104');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount(1, 'response success');

        // Success is returned even if the bookmark is already gone.
        $crawler = $client->request('GET', '/bookmarks/remove/course.CHEM0104');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount(1, 'response success');
    }
}
