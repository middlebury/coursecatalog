<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TopicsTest extends WebTestCase
{
    use \banner_DatabaseTestTrait;

    public function testListAll(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/list');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('a:contains("Hebrew")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Geology")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Chemistry & Biochemistry")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("DED")')->count());
    }

    public function testListByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/list/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('a:contains("Hebrew")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Geology")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Chemistry & Biochemistry")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("DED")')->count());
    }

    public function testListByInvalidCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/list/catalog.XXXX');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testListByCatalogTypeLong(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/list/catalog.MCUG/urn:inet:middlebury.edu:genera:topic.requirement');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('a:contains("Hebrew")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Geology")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Chemistry & Biochemistry")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("DED")')->count());
    }

    public function testListByCatalogTypeShort(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/list/catalog.MCUG/genera:topic.requirement');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('a:contains("Hebrew")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Geology")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Chemistry & Biochemistry")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("DED")')->count());
    }

    public function testListXmlAll(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/listxml');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Hebrew")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Geology")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Chemistry & Biochemistry")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("DED")')->count());
    }

    public function testListXmlByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/listxml/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('item title:contains("Hebrew")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Geology")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Chemistry & Biochemistry")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("DED")')->count());
    }

    public function testListXmlByInvalidCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/listxml/catalog.XXXX');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testListXmlByCatalogTypeLong(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/listxml/catalog.MCUG/urn:inet:middlebury.edu:genera:topic.requirement');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('item title:contains("Hebrew")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Geology")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Chemistry & Biochemistry")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("DED")')->count());
    }

    public function testListXmlByCatalogTypeShort(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/listxml/catalog.MCUG/genera:topic.requirement');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('item title:contains("Hebrew")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Geology")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Chemistry & Biochemistry")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("DED")')->count());
    }

    public function testRecentAll(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/recent');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('a:contains("Hebrew")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Geology")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Chemistry & Biochemistry")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("DED")')->count());
    }

    public function testRecentByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/recent/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('a:contains("Hebrew")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Geology")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Chemistry & Biochemistry")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("DED")')->count());
    }

    public function testRecentByInvalidCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/recent/catalog.XXXX');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testRecentByCatalogTypeLong(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/recent/catalog.MCUG/urn:inet:middlebury.edu:genera:topic.requirement');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('a:contains("Hebrew")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Geology")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Chemistry & Biochemistry")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("DED")')->count());
    }

    public function testRecentByCatalogTypeShort(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/recent/catalog.MCUG/genera:topic.requirement');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('a:contains("Hebrew")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Geology")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Chemistry & Biochemistry")')->count());
        $this->assertEquals(0, $crawler->filter('a:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a:contains("DED")')->count());
    }

    public function testRecentXmlAll(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/recentxml');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Hebrew")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Geology")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Chemistry & Biochemistry")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("DED")')->count());
    }

    public function testRecentXmlByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/recentxml/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('item title:contains("Hebrew")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Geology")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Chemistry & Biochemistry")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("DED")')->count());
    }

    public function testRecentXmlByInvalidCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/terms/recentxml/catalog.XXXX');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testRecentXmlByCatalogTypeLong(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/recentxml/catalog.MCUG/urn:inet:middlebury.edu:genera:topic.requirement');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('item title:contains("Hebrew")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Geology")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Chemistry & Biochemistry")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("DED")')->count());
    }

    public function testRecentXmlByCatalogTypeShort(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/recentxml/catalog.MCUG/genera:topic.requirement');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertEquals(0, $crawler->filter('item title:contains("Hebrew")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Geology")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Chemistry & Biochemistry")')->count());
        $this->assertEquals(0, $crawler->filter('item title:contains("Natural Sciences")')->count());
        $this->assertGreaterThan(0, $crawler->filter('item title:contains("DED")')->count());
    }

    public function testView(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/view/topic.subject.PHYS');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('a.offering_link:contains("PHYS0201A-F09")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a.offering_link:contains("PHYS0201A-F06")')->count());
    }

    public function testViewInvalidId(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/view/topic.subject.XXXX');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testViewByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/view/topic.subject.PHYS/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('a.offering_link:contains("PHYS0201A-F09")')->count());
        $this->assertGreaterThan(0, $crawler->filter('a.offering_link:contains("PHYS0201A-F06")')->count());
    }

    public function testViewByNonMatchingCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/view/topic.subject.PHYS/catalog.BLSE');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testViewByTerm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/view/topic.subject.PHYS/catalog.MCUG/term.200990');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('a.offering_link:contains("PHYS0201A-F09")')->count());
        $this->assertEquals(0, $crawler->filter('a.offering_link:contains("PHYS0201A-F06")')->count());
    }

    public function testViewByNonMatchingTerm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/view/topic.subject.PHYS/catalog.MCUG/term.200900');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testViewXml(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/viewxml/topic.subject.PHYS');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Physics")')->count());
    }

    public function testViewXmlInvalidId(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/viewxml/topic.subject.XXXX');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testViewXmlByCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/viewxml/topic.subject.PHYS/catalog.MCUG');

        $this->assertResponseIsSuccessful();
        // print $crawler->outerHtml();

        $this->assertGreaterThan(0, $crawler->filter('item title:contains("Physics")')->count());
    }

    public function testViewXmlByNonMatchingCatalog(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/topics/viewxml/topic.subject.PHYS/catalog.BLSE');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testListSubjectsTxtAll(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listsubjectstxt');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/Geology/i', $response->getContent());
        $this->assertMatchesRegularExpression('/Hebrew/i', $response->getContent());
    }

    public function testListSubjectsTxtByCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listsubjectstxt/catalog.MCUG');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/Geology/i', $response->getContent());
        $this->assertDoesNotMatchRegularExpression('/Hebrew/i', $response->getContent());
    }

    public function testListDepartmentsTxtAll(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listdepartmentstxt');
        $response = $client->getResponse();

        // print $response->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/Geology/i', $response->getContent());
        $this->assertMatchesRegularExpression('/Geography/i', $response->getContent());
    }

    public function testListDepartmentsTxtByCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listdepartmentstxt/catalog.MCUG');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/Geology/i', $response->getContent());
        $this->assertMatchesRegularExpression('/Geography/i', $response->getContent());
    }

    public function testListDepartmentsTxtByOtherCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listdepartmentstxt/catalog.BLSE');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertDoesNotMatchRegularExpression('/Geology/i', $response->getContent());
        $this->assertDoesNotMatchRegularExpression('/Geography/i', $response->getContent());
    }

    public function testListRequirementsTxtAll(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listrequirementstxt');
        $response = $client->getResponse();

        // print $response->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/DED/i', $response->getContent());
    }

    public function testListRequirementsTxtByCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listrequirementstxt/catalog.MCUG');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/DED/i', $response->getContent());
    }

    public function testListRequirementsTxtByOtherCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listrequirementstxt/catalog.BLSE');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertDoesNotMatchRegularExpression('/DED/i', $response->getContent());
    }

    public function testListLevelsTxtAll(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listlevelstxt');
        $response = $client->getResponse();

        // print $response->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/GR - Graduate/i', $response->getContent());
        $this->assertMatchesRegularExpression('/UG - Undergraduate/i', $response->getContent());
    }

    public function testListLevelsTxtByCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listlevelstxt/catalog.MCUG');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertDoesNotMatchRegularExpression('/GR - Graduate/i', $response->getContent());
        $this->assertMatchesRegularExpression('/UG - Undergraduate/i', $response->getContent());
    }

    public function testListLevelsTxtByOtherCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listlevelstxt/catalog.BLSE');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertDoesNotMatchRegularExpression('/GR - Graduate/i', $response->getContent());
        $this->assertDoesNotMatchRegularExpression('/UG - Undergraduate/i', $response->getContent());
    }

    public function testListBlocksTxtAll(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listblockstxt');
        $response = $client->getResponse();

        // print $response->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/Community Connected Course/i', $response->getContent());
    }

    public function testListBlocksTxtByCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listblockstxt/catalog.MCUG');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/Community Connected Course/i', $response->getContent());
    }

    public function testListBlocksTxtByOtherCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listblockstxt/catalog.BLSE');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertDoesNotMatchRegularExpression('/Community Connected Course/i', $response->getContent());
    }

    public function testListInstructionMethodsTxtAll(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listinstructionmethodstxt');
        $response = $client->getResponse();

        // print $response->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/HYB - Hybrid/i', $response->getContent());
    }

    public function testListInstructionMethodsTxtByCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listinstructionmethodstxt/catalog.MCLS');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/HYB - Hybrid/i', $response->getContent());
    }

    public function testListInstructionMethodsTxtByOtherCatalog(): void
    {
        $client = static::createClient();
        $client->request('GET', '/topics/listinstructionmethodstxt/catalog.MCUG');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertDoesNotMatchRegularExpression('/HYB - Hybrid/i', $response->getContent());
    }
}
