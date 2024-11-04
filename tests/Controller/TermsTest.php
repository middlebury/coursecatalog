<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TermsTest extends WebTestCase
{
    use \banner_DatabaseTestTrait;

    public function testListAll(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/list');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('a:contains("Fall 2009")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Summer 200")')->count());
    }

    public function testListByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/list/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('a:contains("Fall 2009")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Summer 200")')->count());
    }

    public function testListByInvalidCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/list/catalog.XXXX');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testListXmlAll(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/listxml');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Fall 2009")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Summer 200")')->count());
    }

    public function testListXmlByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/listxml/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Fall 2009")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Summer 200")')->count());
    }

    public function testListXmlByInvalidCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/listxml/catalog.XXXX');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testView(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/view/term.200990');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('a.offering_link')->count());
    }

    public function testViewInvalidId(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/view/term.200900');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testViewByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/view/term.200990/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('a.offering_link')->count());
    }

    public function testViewByNonMatchingCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/view/term.200990/catalog.MIIS');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testDetails(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/details/term.200990');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('dl dd:contains("2009-09-07")')->count());
    }

    public function testDetailsInvalidId(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/details/term.200900');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testDetailsByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/details/term.200990/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('dl dd:contains("2009-09-07")')->count());
    }

    public function testDetailsByNonMatchingCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/details/term.200990/catalog.MIIS');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testDetailsXml(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/detailsxml/term.200990');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('term start_date:contains("2009-09-07")')->count());
    }

    public function testDetailsXmlInvalidId(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/detailsxml/term.200900');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testDetailsXmlByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/detailsxml/term.200990/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('term start_date:contains("2009-09-07")')->count());
    }

    public function testDetailsXmlByNonMatchingCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/detailsxml/term.200990/catalog.MIIS');

        $this->assertResponseStatusCodeSame(404);
    }
}
