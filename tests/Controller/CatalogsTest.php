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
}
