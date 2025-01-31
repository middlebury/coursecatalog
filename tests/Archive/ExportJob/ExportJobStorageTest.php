<?php

namespace App\Tests\Archive\ExportJob;

use App\Archive\ExportConfiguration\ExportConfigurationStorage;
use App\Archive\ExportJob\ExportJobStorage;
use App\Security\SamlUser;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Tests\AppDatabaseTestTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\SecurityBundle\Security;

class ExportJobStorageTest extends KernelTestCase
{
    use AppDatabaseTestTrait;

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

        $this->jobStorage = static::getContainer()
            ->get(ExportJobStorage::class);

        $this->osidIdMap = static::getContainer()
            ->get(IdMap::class);
    }

    public function testCreateJob(): void
    {
        $config = $this->configStorage->createConfiguration(
            'My great config',
            $this->osidIdMap->fromString('catalog.MCUG')
        );
        $job = $this->jobStorage->createJob(
            'MCUG/2009-2010/',
            $config->getId(),
            null,
            '200990,201020'
        );
        $this->assertEquals('MCUG/2009-2010/', $job->getExportPath());
        $this->assertTrue($job->getActive());
        $this->assertEquals($config->getId(), $job->getConfigurationId());
        $this->assertNull($job->getRevisionId());
        $this->assertEquals('200990,201020', $job->getTerms());

        $job->delete();
        $config->delete();
    }

    public function testUpdateJob(): void
    {
        $config = $this->configStorage->createConfiguration(
            'My great config',
            $this->osidIdMap->fromString('catalog.MCUG')
        );
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
        $job = $this->jobStorage->createJob(
            'MCUG/2009-202010/',
            $config->getId(),
            null,
            '200990,201020'
        );

        $job->setActive(false);
        $job->setExportPath('MCUG/2010-2011/');
        $job->setRevisionId($revision->getId());
        $job->setTerms('201090,201120');
        $job->save();

        $job = $this->jobStorage->getJob($job->getId());

        $this->assertEquals('MCUG/2010-2011/', $job->getExportPath());
        $this->assertFalse($job->getActive());
        $this->assertEquals($config->getId(), $job->getConfigurationId());
        $this->assertEquals($revision->getId(), $job->getRevisionId());
        $this->assertEquals('201090,201120', $job->getTerms());

        $job->delete();
        $config->delete();
    }
}
