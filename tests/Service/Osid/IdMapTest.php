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

    public function testGetOsidTypeFromString()
    {
        $type = new \phpkit_type_Type('urn', 'example.edu', '123456789.abcd');
        $typeString = $this->osidIdMap->typeToString($type);
        $this->assertEquals('123456789.abcd', $typeString);

        $newType = $this->osidIdMap->typeFromString($typeString);
        $this->assertInstanceOf('osid_type_Type', $newType);
        $this->assertTrue($type->isEqual($newType));
    }

    public function testOtherTypeAuthority()
    {
        $shortenedType = new \phpkit_type_Type('urn', 'example.edu', '123456789.abcd');
        $shortenedTypeString = $this->osidIdMap->typeToString($shortenedType);
        $otherType = new \phpkit_type_Type('urn', 'example.com', '123456789.abcd');
        $otherTypeString = $this->osidIdMap->typeToString($otherType);
        $this->assertNotEquals($shortenedTypeString, $otherTypeString);

        $newType = $this->osidIdMap->typeFromString($otherTypeString);
        $this->assertInstanceOf('osid_type_Type', $newType);
        $this->assertTrue($otherType->isEqual($newType));
        $this->assertFalse($shortenedType->isEqual($newType));
    }
}
