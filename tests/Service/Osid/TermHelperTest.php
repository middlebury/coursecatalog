<?php

namespace App\Tests\Service\Osid;

use App\Service\Osid\Runtime;
use App\Service\Osid\TermHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TermHelperTest extends KernelTestCase
{
    use \banner_DatabaseTestTrait;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->mcugId = new \phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog.MCUG');
        $this->spring2009TermId = new \phpkit_id_URNInetId('urn:inet:middlebury.edu:term.200920');
        $this->fall2009TermId = new \phpkit_id_URNInetId('urn:inet:middlebury.edu:term.200990');
        $this->fall2008TermId = new \phpkit_id_URNInetId('urn:inet:middlebury.edu:term.200890');

        $this->osidTermHelper = static::getContainer()
            ->get(TermHelper::class);
        $this->termLookup = static::getContainer()
            ->get(Runtime::class)
            ->getCourseManager()
            ->getTermLookupSessionForCatalog($this->mcugId);
    }

    public function testGetCurrentTermId()
    {
        $this->assertInstanceOf('osid_id_Id', $this->osidTermHelper->getCurrentTermId($this->mcugId));
    }

    public function testFindClosestTermId()
    {
        $testDate = new \DateTime('2009-09-30');
        $terms = $this->termLookup->getTerms();
        $closestTermId = $this->osidTermHelper->findClosestTermId($terms, $testDate);
        $this->assertTrue($closestTermId->isEqual($this->fall2009TermId));
    }

    public function testFindlosestNonOverlappingTermIdA()
    {
        $testDate = new \DateTime('2009-08-15');
        $terms = $this->termLookup->getTerms();
        $closestTermId = $this->osidTermHelper->findClosestTermId($terms, $testDate);
        $this->assertTrue($closestTermId->isEqual($this->fall2009TermId));
    }

    public function testFindClosestNonOverlappingTermIdB()
    {
        $testDate = new \DateTime('2009-06-15');
        $terms = $this->termLookup->getTerms();
        $closestTermId = $this->osidTermHelper->findClosestTermId($terms, $testDate);
        $this->assertTrue($closestTermId->isEqual($this->spring2009TermId));
    }

    public function testFindClosestTermIdBeyondRange()
    {
        $testDate = new \DateTime('2020-01-01');
        $terms = $this->termLookup->getTerms();
        $closestTermId = $this->osidTermHelper->findClosestTermId($terms, $testDate);
        $this->assertTrue($closestTermId->isEqual($this->fall2009TermId));
    }
}
