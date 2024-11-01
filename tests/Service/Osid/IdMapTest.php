<?php

namespace App\Tests\Service\Osid;

use App\Service\Osid\IdMap;
use PHPUnit\Framework\TestCase;

class IdMapTest extends TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->osidIdMap = new IdMap('example.edu');
    }

    public function testGetOsidIdFromString()
    {
        $id = new \phpkit_id_Id('example.edu', 'urn', '123456789.abcd');
        $idString = $this->osidIdMap->toString($id);
        $this->assertEquals('123456789.abcd', $idString);

        $newId = $this->osidIdMap->fromString($idString);
        $this->assertInstanceOf('osid_id_Id', $newId);
        $this->assertTrue($id->isEqual($newId));
    }

    public function testGetStringFromOsidId()
    {
        $id = new \phpkit_id_Id('example.edu', 'urn', '123456789.abcd');
        $idString = $this->osidIdMap->toString($id);
        $this->assertIsString($idString);

        $newId = $this->osidIdMap->fromString($idString);
        $this->assertEquals($idString, $this->osidIdMap->toString($newId));
    }

    public function testOtherAuthority()
    {
        $shortenedId = new \phpkit_id_Id('example.edu', 'urn', '123456789.abcd');
        $shortenedIdString = $this->osidIdMap->toString($shortenedId);
        $otherId = new \phpkit_id_Id('example.com', 'urn', '123456789.abcd');
        $otherIdString = $this->osidIdMap->toString($otherId);
        $this->assertNotEquals($shortenedIdString, $otherIdString);

        $newId = $this->osidIdMap->fromString($otherIdString);
        $this->assertInstanceOf('osid_id_Id', $newId);
        $this->assertTrue($otherId->isEqual($newId));
        $this->assertFalse($shortenedId->isEqual($newId));
    }
}
