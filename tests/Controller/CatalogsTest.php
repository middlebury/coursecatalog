<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CatalogsTest extends WebTestCase
{
    use \banner_DatabaseTestTrait;

    public function testHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertGreaterThan(0, $crawler->filter('a:contains("Middlebury College")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Middlebury College Language Schools")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Monterey Institute of International Studies")')->count());
    }

    public function testList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/catalogs/list');

        $this->assertResponseIsSuccessful();

        $this->assertGreaterThan(0, $crawler->filter('a:contains("Middlebury College")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Middlebury College Language Schools")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Monterey Institute of International Studies")')->count());
    }

    public function testListXml(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/catalogs/listxml');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item[title[contains(text(), "Monterey Institute of International Studies")]]');
        $this->assertEquals(1, $item->count());
        $this->assertGreaterThan(0, $item->filter('title:contains("Monterey Institute of International Studies")')->count());
        $this->assertGreaterThan(0, $item->filter('catalog\:id:contains("catalog.MIIS")')->count());
    }

    public function testView(): void
    {
        $client = static::createClient();
        $client->followRedirects(false);
        $crawler = $client->request('GET', '/catalogs/view/catalog.MCUG');

        $this->assertResponseRedirects('/offerings/search/catalog.MCUG?termId=term.200990');
    }

    public function testViewWithTerm(): void
    {
        $client = static::createClient();
        $client->followRedirects(false);
        $crawler = $client->request('GET', '/catalogs/view/catalog.MCUG/term.200710');

        $this->assertResponseRedirects('/offerings/search/catalog.MCUG?termId=term.200710');
    }

    public function testViewXml(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/catalogs/viewxml/catalog.MCUG');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(1, $item->count());
        $this->assertGreaterThan(0, $item->filter('title:contains("Middlebury College")')->count());
        $this->assertGreaterThan(0, $item->filter('catalog\:id:contains("catalog.MCUG")')->count());
    }
}
