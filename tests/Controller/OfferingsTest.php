<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferingsTest extends WebTestCase
{
    use \banner_DatabaseTestTrait;

    public function testList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offerings/list/catalog.MCUG/term.200690');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('a:contains("PHYS0201A-F06")')->count());
    }

    public function testListCurrent(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offerings/list/catalog.MCUG/CURRENT');

        $this->assertResponseIsSuccessful();

        $this->assertGreaterThan(0, $crawler->filter('a:contains("PHYS0201A-F09")')->count());
    }

    public function testView(): void
    {
        $client = static::createClient();
        $client->followRedirects(false);
        $crawler = $client->request('GET', '/offerings/view/section.200990.90036');

        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter('h2:contains("General Chemistry II")')->count());
    }

    public function testViewXml(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offerings/viewxml/section.200990.90036');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('channel item catalog\:title', 'General Chemistry II');
    }

    public function testSearchForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offerings/search/catalog.MCUG');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('form');
    }

    public function testSearchFormSubmitAnyTerm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offerings/search/catalog.MCUG?search=Search&term=ANY');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('.search_results', 'General Chemistry II');
    }

    public function testSearchFormSubmitWithTerm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offerings/search/catalog.MCUG?search=Search&term=term.200990');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('.search_results', 'General Chemistry II');
    }

    public function testSearchFormSubmitWithTermAndDepartment(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offerings/search/catalog.MCUG?search=Search&term=term.200990&department=topic.department.CHEM');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('.search_results', 'General Chemistry II');
    }

    public function testSearchFormSubmitWithKeyword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offerings/search/catalog.MCUG?search=Search&term=term.200990&keyword=General');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('.search_results', 'General Chemistry II');
        $this->assertSelectorTextContains('.search_results', 'CHEM0104Z-F09');
    }

    public function testSearchFormSubmitType(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offerings/search/catalog.MCUG?search=Search&term=term.200990&keyword=General&type%5B%5D=genera%3Aoffering.LCT&type%5B%5D=genera%3Aoffering.SEM');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('.search_results', 'General Chemistry II');
        $this->assertSelectorTextNotContains('.search_results', 'CHEM0104Z-F09');
    }

    public function testSearchXmlWithTermAndDepartment(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/offerings/searchxml/catalog.MCUG?search=Search&term=term.200990&department=topic.department.CHEM');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();
        $this->assertSelectorTextContains('catalog\:title', 'General Chemistry II');
    }
}
