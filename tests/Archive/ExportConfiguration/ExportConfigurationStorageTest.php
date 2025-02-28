<?php

namespace App\Tests\Archive\ExportConfiguration;

use App\Archive\ExportConfiguration\ExportConfigurationStorage;
use App\Security\SamlUser;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Tests\AppDatabaseTestTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\SecurityBundle\Security;

class ExportConfigurationStorageTest extends KernelTestCase
{
    use AppDatabaseTestTrait;

    private ExportConfigurationStorage $configStorage;
    private IdMap $osidIdMap;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $user = new SamlUser('WEBID99999990');
        $user->setSamlAttributes([
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress' => ['honeybear@middlebury.edu'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname' => ['Winnie'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname' => ['The-Pooh'],
            'AssignedRoles' => ['App.EmailSendAllowed'],
        ]);
        $mockSecurity = $this->createMock(Security::class);
        $mockSecurity->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($user));

        $this->configStorage = new ExportConfigurationStorage(
            static::getContainer()->get(EntityManagerInterface::class),
            static::getContainer()->get(Runtime::class),
            static::getContainer()->get(IdMap::class),
            $mockSecurity,
        );

        $this->osidIdMap = static::getContainer()
            ->get(IdMap::class);
    }

    public function testCreateConfiguration(): void
    {
        $newConfig = $this->configStorage->createConfiguration('My great config', $this->osidIdMap->fromString('catalog-MCUG'));
        $this->assertEquals('My great config', $newConfig->getLabel());
        $this->assertGreaterThan(0, $newConfig->getId());
        $this->assertEmpty($newConfig->getAllRevisions());
        $newConfig->delete();
    }

    public function testCreateRevisions(): void
    {
        $config = $this->configStorage->createConfiguration('My great config', $this->osidIdMap->fromString('catalog-MCUG'));
        $this->assertEquals('My great config', $config->getLabel());
        $this->assertEmpty($config->getAllRevisions());

        // Add a first revision.
        $content = [
            'group1' => [
                'title' => 'Test section',
                'section1' => [
                    'type' => 'h1',
                    'value' => 'Test section',
                ],
            ],
        ];
        $revision = $config->createRevision($content, 'First revision note.');
        $this->assertEquals('First revision note.', $revision->getNote());
        $this->assertEquals($content, $revision->getContent());
        $this->assertEquals('WEBID99999990', $revision->getUserId());
        $this->assertEquals('Winnie The-Pooh', $revision->getUserDisplayName());

        $this->assertCount(1, $config->getAllRevisions());

        // Add a second revision.
        $content = [
            'group1' => [
                'title' => 'Test section',
                'section1' => [
                    'type' => 'h1',
                    'value' => 'Test section',
                ],
                'section2' => [
                    'type' => 'text',
                    'value' => 'Hello World',
                ],
            ],
        ];
        $revision = $config->createRevision($content, 'Second revision note.');
        $this->assertEquals('Second revision note.', $revision->getNote());
        $this->assertEquals($content, $revision->getContent());
        $this->assertEquals('WEBID99999990', $revision->getUserId());
        $this->assertEquals('Winnie The-Pooh', $revision->getUserDisplayName());

        $this->assertCount(2, $config->getAllRevisions());
        $this->assertEquals($revision->getId(), $config->getLatestRevision()->getId());

        $config->delete();
    }
}
