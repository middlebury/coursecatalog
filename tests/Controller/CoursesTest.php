<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoursesTest extends WebTestCase
{
    use \banner_DatabaseTestTrait;

    public function testList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/list/catalog.MCUG');

        $this->assertResponseIsSuccessful();

        $this->assertGreaterThan(0, $crawler->filter('a:contains("PHYS 0201")')->count());
    }

    public function testView(): void
    {
        $client = static::createClient();
        $client->followRedirects(false);
        $crawler = $client->request('GET', '/courses/view/course.PHYS0201');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.offering_list a.offering_link', 'PHYS0201A-F09');
    }

    public function testViewWithTerm(): void
    {
        $client = static::createClient();
        $client->followRedirects(false);
        $crawler = $client->request('GET', '/courses/view/course.PHYS0201/term.200690');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div.offering_list a.offering_link', 'PHYS0201A-F06');
    }

    public function testViewXml(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/viewxml/course.PHYS0201');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('catalog\:offering_list catalog\:offering_term[name]', 'Fall 2009');
    }

    public function testViewXmlWithTerm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/viewxml/course.PHYS0201/term.200690');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('catalog\:offering_list catalog\:offering_term[name]', 'Fall 2006');
    }
}
