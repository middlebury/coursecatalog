<?php

namespace App\Tests\Archive\Export;

use App\Archive\Export\ArchiveFileManager;
use App\Archive\ExportJob\ExportJob;
use App\Archive\Storage\ArchiveStorage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ArchiveFileManagerTest extends KernelTestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $projectDir = static::getContainer()->getParameter('kernel.project_dir');
        $this->archiveBase = $projectDir.'/var/test/archive';

        // Make sure that our directory exists.
        if (!is_dir($projectDir.'/var/test')) {
            mkdir($projectDir.'/var/test');
        }
        if (!is_dir($projectDir.'/var/test/archive')) {
            mkdir($projectDir.'/var/test/archive');
        }

        $this->archiveStorage = new ArchiveStorage(
            'var/test/archive',
            static::getContainer()->get(Filesystem::class),
            $projectDir,
        );

        $this->archiveFileManager = new ArchiveFileManager(
            $this->archiveStorage,
            static::getContainer()->get(EventDispatcherInterface::class),
        );
    }

    protected function tearDown(): void
    {
        // Delete our test directories.
        $projectDir = static::getContainer()->getParameter('kernel.project_dir');
        $this->recursivelyRemoveDirectory($projectDir.'/var/test');
    }

    protected function recursivelyRemoveDirectory($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ('.' != $object && '..' != $object) {
                    if (is_dir($dir.DIRECTORY_SEPARATOR.$object) && !is_link($dir.DIRECTORY_SEPARATOR.$object)) {
                        $this->recursivelyRemoveDirectory($dir.DIRECTORY_SEPARATOR.$object);
                    } else {
                        unlink($dir.DIRECTORY_SEPARATOR.$object);
                    }
                }
            }
            rmdir($dir);
        }
    }

    public function testArchiveFileWriting(): void
    {
        $exportPath = 'TEST/2025-2026';
        $mockJob = $this->createMock(ExportJob::class);
        $mockJob->expects($this->any())
            ->method('getExportPath')
            ->willReturn($exportPath);
        $this->archiveFileManager->setDate(new \DateTime('2025-01-02'));

        // Ensure that our file and directory don't exist.
        $this->assertDirectoryDoesNotExist($this->archiveBase.'/'.$exportPath);
        $this->assertFalse($this->archiveStorage->exists($exportPath));

        $this->archiveFileManager->updateArchive($mockJob, 'Hello world.');
        // Ensure that our directory exists.
        $this->assertDirectoryExists($this->archiveBase.'/'.$exportPath);
        $this->assertTrue($this->archiveStorage->exists($exportPath));
        // Ensure that our file exists.
        $this->assertFileExists($this->archiveBase.'/'.$exportPath.'/html/TEST-2025-2026_snapshot-2025-01-02.html');
        $this->assertTrue($this->archiveStorage->exists($exportPath.'/html/TEST-2025-2026_snapshot-2025-01-02.html'));
        $this->assertEquals(
            'Hello world.',
            $this->archiveStorage->get($exportPath.'/html/TEST-2025-2026_snapshot-2025-01-02.html')->getFileContent()
        );
        // Ensure that our link exists.
        $this->assertFileExists($this->archiveBase.'/'.$exportPath.'/TEST-2025-2026_latest.html');
        $this->assertTrue($this->archiveStorage->exists($exportPath.'/TEST-2025-2026_latest.html'));
        $this->assertEquals(
            'html/TEST-2025-2026_snapshot-2025-01-02.html',
            $this->archiveStorage->get($exportPath.'/TEST-2025-2026_latest.html')->getTarget()
        );
        $this->assertEquals(
            'Hello world.',
            $this->archiveStorage->get($exportPath.'/TEST-2025-2026_latest.html')->getFileContent()
        );
    }

    public function testArchiveUpdatingOverDays(): void
    {
        $exportPath = 'TEST/2025-2026';
        $mockJob = $this->createMock(ExportJob::class);
        $mockJob->expects($this->any())
            ->method('getExportPath')
            ->willReturn($exportPath);
        $this->archiveFileManager->setDate(new \DateTime('2025-01-02'));

        // Ensure that our file and directory don't exist.
        $this->assertDirectoryDoesNotExist($this->archiveBase.'/'.$exportPath);
        $this->assertFalse($this->archiveStorage->exists($exportPath));

        // Write the first file.
        $this->archiveFileManager->updateArchive(
            $mockJob,
            $this->getSampleHtmlForDate(new \DateTime('2025-01-02'), 'Hello world.')
        );

        // Verify that we have our files and links.
        $this->assertTrue($this->archiveStorage->exists($exportPath.'/html/TEST-2025-2026_snapshot-2025-01-02.html'));
        $this->assertEquals(
            'html/TEST-2025-2026_snapshot-2025-01-02.html',
            $this->archiveStorage->get($exportPath.'/TEST-2025-2026_latest.html')->getTarget()
        );

        // Move ahead one day and re-export with the same content.
        $this->archiveFileManager->setDate(new \DateTime('2025-01-03'));
        $this->archiveFileManager->updateArchive(
            $mockJob,
            $this->getSampleHtmlForDate(new \DateTime('2025-01-03'), 'Hello world.')
        );

        // Verify that a new file wasn't created and the link still points at our previous export.
        $this->assertFalse($this->archiveStorage->exists($exportPath.'/html/TEST-2025-2026_snapshot-2025-01-03.html'));
        $this->assertEquals(
            'html/TEST-2025-2026_snapshot-2025-01-02.html',
            $this->archiveStorage->get($exportPath.'/TEST-2025-2026_latest.html')->getTarget()
        );

        // Move ahead another day and export different content.
        $this->archiveFileManager->setDate(new \DateTime('2025-01-04'));
        $this->archiveFileManager->updateArchive(
            $mockJob,
            $this->getSampleHtmlForDate(new \DateTime('2025-01-04'), 'I am cheese.')
        );

        // Verify that a new file wasn't created and the link still points at our previous export.
        $this->assertTrue($this->archiveStorage->exists($exportPath.'/html/TEST-2025-2026_snapshot-2025-01-04.html'));
        $this->assertEquals(
            'html/TEST-2025-2026_snapshot-2025-01-04.html',
            $this->archiveStorage->get($exportPath.'/TEST-2025-2026_latest.html')->getTarget()
        );

        // Move ahead another day and export same content.
        $this->archiveFileManager->setDate(new \DateTime('2025-01-05'));
        $this->archiveFileManager->updateArchive(
            $mockJob,
            $this->getSampleHtmlForDate(new \DateTime('2025-01-05'), 'I am cheese.')
        );

        // Verify that a new file wasn't created and the link still points at our previous export.
        $this->assertFalse($this->archiveStorage->exists($exportPath.'/html/TEST-2025-2026_snapshot-2025-01-05.html'));
        $this->assertEquals(
            'html/TEST-2025-2026_snapshot-2025-01-04.html',
            $this->archiveStorage->get($exportPath.'/TEST-2025-2026_latest.html')->getTarget()
        );

        // Move ahead another day and export different content.
        $this->archiveFileManager->setDate(new \DateTime('2025-01-06'));
        $this->archiveFileManager->updateArchive(
            $mockJob,
            $this->getSampleHtmlForDate(new \DateTime('2025-01-06'), 'The sky is blue.')
        );

        // Verify that a new file wasn't created and the link still points at our previous export.
        $this->assertTrue($this->archiveStorage->exists($exportPath.'/html/TEST-2025-2026_snapshot-2025-01-06.html'));
        $this->assertEquals(
            'html/TEST-2025-2026_snapshot-2025-01-06.html',
            $this->archiveStorage->get($exportPath.'/TEST-2025-2026_latest.html')->getTarget()
        );
    }

    protected function getSampleHtmlForDate(\DateTime $date, $extraText)
    {
        return <<<END
<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />

        <title>Course Catalog - Middlebury College - Fall 2024, Spring 2025</title>
        <link href="/StyleSheets/Archive.css" media="all" rel="stylesheet" />


    </head>
    <body>
        <section class='catalog'>
            <header class='catalog_header'>
                <a name='top' class='local_anchor'></a>
                <h1>Course Catalog - Middlebury College - Fall 2024, Spring 2025</h1>
                <button class='print_button' onclick='javascript:window.print();'>Print...</button>
                <div class='generated_date'>Generated on <time datetime='{$date->format('c')}'>{$date->format('r')}</time>.</div>
            </header>
            <section class='program'>
                <a name='African-American-Studies-Minor' class='local_anchor'></a>
                <a href='#top' class='jump_link'>&uarr; Top</a>
                <h1>African American Studies Minor</h1>
                <article class='requirements'>
                    <p>This program offers a minor in African American studies to students who complete the following requirements:</p>
                </article>
            </section>
            <section class='program'>
                $extraText
            </section>
        </section>
    </body>
</html>
END;
    }
}
