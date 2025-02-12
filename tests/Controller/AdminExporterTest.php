<?php

namespace App\Tests\Controller;

use App\Archive\ExportJob\ExportJob;
use App\Archive\ExportJob\ExportJobStorage;
use App\Security\SamlUser;
use App\Tests\AppDatabaseTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminExporterTest extends WebTestCase
{
    use AppDatabaseTestTrait;

    private $client;
    private $mockJob1;
    private $mockJob2;
    private $mockJob3;
    private $mockJobStorage;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        // This is needed to allow overriding of the ExportJobStorage with our
        // mock.
        $this->client->disableReboot();

        // Create a mock jobs and job storage.
        $this->mockJob1 = $this->createMock(ExportJob::class);
        $this->mockJob1->expects($this->any())
            ->method('getId')
            ->willReturn(1);
        $this->mockJob1->expects($this->any())
            ->method('getActive')
            ->willReturn(true);

        $this->mockJob2 = $this->createMock(ExportJob::class);
        $this->mockJob2->expects($this->any())
            ->method('getId')
            ->willReturn(2);
        $this->mockJob2->expects($this->any())
            ->method('getActive')
            ->willReturn(false);

        $this->mockJob3 = $this->createMock(ExportJob::class);
        $this->mockJob3->expects($this->any())
            ->method('getId')
            ->willReturn(3);
        $this->mockJob3->expects($this->any())
            ->method('getActive')
            ->willReturn(true);

        $this->mockJobStorage = $this->createMock(ExportJobStorage::class);
        $this->mockJobStorage->expects($this->any())
            ->method('getJob')
            ->willReturnCallback([$this, 'getJob']);
        $this->mockJobStorage->expects($this->any())
            ->method('getAllJobs')
            ->willReturn([$this->mockJob1, $this->mockJob2, $this->mockJob3]);

        // Use our mock job storage for our client.
        $this->client->getContainer()->set(ExportJobStorage::class, $this->mockJobStorage);

        $this->client->loginUser($this->setUpUser());
    }

    public function getJob($jobId)
    {
        if (1 == $jobId) {
            return $this->mockJob1;
        } elseif (2 == $jobId) {
            return $this->mockJob2;
        } elseif (3 == $jobId) {
            return $this->mockJob3;
        } else {
            throw new \InvalidArgumentException('Unknown job.');
        }
    }

    private function setUpUser(): SamlUser
    {
        $user = new SamlUser('WEBID99999990');
        $user->setSamlAttributes([
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress' => ['honeybear@middlebury.edu'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname' => ['Winnie'],
            'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname' => ['The-Pooh'],
            'AssignedRoles' => ['App.EmailSendAllowed', 'App.Manager'],
        ]);

        return $user;
    }

    public function testSubmitSingleJob(): void
    {
        $crawler = $this->client->request('GET', '/admin/exports/jobs');
        // Get the CSRF key from our other action.
        $csrfToken = $crawler->filter('#jobs')->attr('data-run-job-csrf_key');

        $crawler = $this->client->request('POST', '/admin/exports/run/single/1', ['csrf_key' => $csrfToken]);
        $this->assertResponseIsSuccessful();

        // ensure that the message was regeistered.
        $transport = $this->client->getContainer()->get('messenger.transport.async');
        $messages = $transport->get();
        $this->assertCount(1, $messages);
        $this->assertEquals(1, $messages[0]->getMessage()->getJobId());
    }

    public function testSubmitAllActiveJob(): void
    {
        $crawler = $this->client->request('GET', '/admin/exports/jobs');
        // Get the CSRF key from our other action.
        $csrfToken = $crawler->filter('#jobs')->attr('data-run-job-csrf_key');

        $crawler = $this->client->request('POST', '/admin/exports/run/active', ['csrf_key' => $csrfToken]);
        $this->assertResponseIsSuccessful();

        // ensure that the message was regeistered.
        $transport = $this->client->getContainer()->get('messenger.transport.async');
        $messages = $transport->get();
        $this->assertCount(2, $messages);
        $this->assertEquals(1, $messages[0]->getMessage()->getJobId());
        $this->assertEquals(3, $messages[1]->getMessage()->getJobId());
    }
}
