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

    public function testSearchXml(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/searchxml/catalog.MCUG?keywords=Arctic%20and%20Alpine');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item[catalog:id[contains(text(), "course.GEOL0250")]]');
        $this->assertEquals(1, $item->count());
        $this->assertGreaterThan(0, $item->filter('title:contains("Arctic and Alpine Environments")')->count());
    }

    public function testSearchXmlWithNoKeywords(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/searchxml/catalog.MCUG');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(0, $item->count());
    }

    public function testTopicXml(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/topicxml/catalog.MCUG?topic[]=topic.department.GEOL&topic[]=topic.department.GEOG');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(2, $item->count());
    }

    public function testTopicXmlWithUnknownTopic(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/topicxml/catalog.MCUG?topic[]=topic.subject.GEOL&topic[]=topic.subject.XXXX');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(1, $item->count());
    }

    public function testTopicXmlWithLocation(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/topicxml/catalog.MCUG?topic[]=topic.department.GEOL&location[]=resource.place.campus.M');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(1, $item->count());
    }

    public function testTopicXmlWithCustomCutoff(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/topicxml/catalog.MCUG?topic[]=topic.department.GEOL&cutoff=P1M');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(0, $item->count());
    }

    public function testByIdXml(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/byidxml/catalog.MCUG?id[]=course.PHYS0201&id[]=course.GEOL0250');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(2, $item->count());
    }

    public function testByIdXmlWithCustomCutoff(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/byidxml/catalog.MCUG?id[]=course.PHYS0201&id[]=course.GEOL0250&cutoff=P1M');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(1, $item->count());
    }

    public function testInstructorXml(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/instructorxml/resource.person.WEBID1000004');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(1, $item->count());
    }

    public function testInstructorXmlWithCutoff(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/instructorxml/resource.person.WEBID1000004?cutoff=P10Y');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(2, $item->count());
    }

    public function testInstructorXmlWithPlainId(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/instructorxml/WEBID1000004');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(1, $item->count());
    }

    public function testInstructorXmlWithCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/instructorxml/resource.person.WEBID1000004/catalog.MCUG');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(1, $item->count());
    }

    public function testInstructorXmlWithOtherCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/instructorxml/resource.person.WEBID1000004/catalog.BLSE');

        $this->assertResponseIsSuccessful();

        $item = $crawler->filterXpath('//item');
        $this->assertEquals(0, $item->count());
    }
}
