<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResourcesTest extends WebTestCase
{
    use \banner_DatabaseTestTrait;

    public function testListCampusesAll(): void
    {
        $client = static::createClient();
        $client->request('GET', '/resources/listcampusestxt');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/Main/i', $response->getContent());
        $this->assertMatchesRegularExpression('/Off-Campus/i', $response->getContent());
    }

    public function testListCampusesByCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/resources/listcampusestxt/catalog.MCUG');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/Main/i', $response->getContent());
        $this->assertDoesNotMatchRegularExpression('/Off-Campus/i', $response->getContent());
    }

    public function testViewPerson(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/resources/view/resource.person.WEBID1000004');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertSelectorTextContains('h1', 'Dudley Derringer');

        $this->assertGreaterThan(0, $crawler->filter('.offering_list h4:contains("Fall 2008")')->count());
        $this->assertGreaterThan(0, $crawler->filter('.offering_list h4:contains("Fall 2003")')->count());
    }

    public function testViewPersonTerm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/resources/view/resource.person.WEBID1000004/term.200890');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertSelectorTextContains('h1', 'Dudley Derringer');

        $this->assertGreaterThan(0, $crawler->filter('.offering_list h4:contains("Fall 2008")')->count());
        $this->assertEquals(0, $crawler->filter('.offering_list h4:contains("Fall 2003")')->count());
    }

    public function testViewRoom(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/resources/view/resource.place.room.MBH.560');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertSelectorTextContains('h1', 'McCardell Bicentennial Hall 560');

        $this->assertGreaterThan(0, $crawler->filter('.offering_list h4:contains("Fall 2009")')->count());
        $this->assertGreaterThan(0, $crawler->filter('.offering_list h4:contains("Spring 2009")')->count());
        $this->assertGreaterThan(0, $crawler->filter('.offering_list h4:contains("Fall 2008")')->count());
    }

    public function testViewRoomTerm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/resources/view/resource.place.room.MBH.560/term.200990');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertSelectorTextContains('h1', 'McCardell Bicentennial Hall 560');

        $this->assertGreaterThan(0, $crawler->filter('.offering_list h4:contains("Fall 2009")')->count());
        $this->assertEquals(0, $crawler->filter('.offering_list h4:contains("Spring 2009")')->count());
        $this->assertEquals(0, $crawler->filter('.offering_list h4:contains("Fall 2008")')->count());
    }
}
