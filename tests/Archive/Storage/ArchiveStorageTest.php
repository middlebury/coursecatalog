<?php

namespace App\Tests\Archive\Storage;

use App\Archive\Storage\ArchiveStorage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class ArchiveStorageTest extends KernelTestCase
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

    public function testFileCreationAndDelete(): void
    {
        $filePath = 'MyCatalogId/testFileCreationAndDelete/html/test.txt';
        $dirPath = 'MyCatalogId/testFileCreationAndDelete/html';
        // Ensure that our file and directory don't exist.
        $this->assertFileDoesNotExist($this->archiveBase.'/'.$filePath);
        $this->assertFalse($this->archiveStorage->exists($filePath));
        $this->assertFileDoesNotExist($this->archiveBase.'/'.$dirPath);
        $this->assertFalse($this->archiveStorage->exists($dirPath));

        // Create the file.
        $archiveFile = $this->archiveStorage->writeFile($filePath, 'Hello world.');
        $this->assertEquals($filePath, $archiveFile->path());
        $this->assertEquals('Hello world.', $archiveFile->getFileContent());
        // Verify that our file and directory were created.
        $this->assertFileExists($this->archiveBase.'/'.$filePath);
        $this->assertTrue($this->archiveStorage->exists($filePath));
        $this->assertFileExists($this->archiveBase.'/'.$dirPath);
        $this->assertTrue($this->archiveStorage->exists($dirPath));

        // Remove the file.
        $this->archiveStorage->delete($filePath);
        // Ensure that our file and directory don't exist.
        $this->assertFileDoesNotExist($this->archiveBase.'/'.$filePath);
        $this->assertFalse($this->archiveStorage->exists($filePath));
        $this->archiveStorage->delete($dirPath);
        $this->assertFileDoesNotExist($this->archiveBase.'/'.$dirPath);
        $this->assertFalse($this->archiveStorage->exists($dirPath));
    }

    public function testRename(): void
    {
        $filePath = 'MyCatalogId/testRename/tmp/test.txt';
        // Ensure that our file and directory don't exist.
        $this->assertFileDoesNotExist($this->archiveBase.'/'.$filePath);
        $this->assertFalse($this->archiveStorage->exists($filePath));

        // Create the file.
        $archiveFile = $this->archiveStorage->writeFile($filePath, 'Hello world.');
        $this->assertEquals($filePath, $archiveFile->path());

        $newFilePath = 'MyCatalogId/testRename/html/test.txt';
        $this->assertFileDoesNotExist($this->archiveBase.'/'.$newFilePath);
        $this->assertFalse($this->archiveStorage->exists($newFilePath));

        // Rename the file.
        $this->archiveStorage->rename($filePath, $newFilePath);
        // Verify that our file was moved.
        $this->assertFileDoesNotExist($this->archiveBase.'/'.$filePath);
        $this->assertFalse($this->archiveStorage->exists($filePath));
        $this->assertFileExists($this->archiveBase.'/'.$newFilePath);
        $this->assertTrue($this->archiveStorage->exists($newFilePath));
    }

    public function testSymbolicLinking(): void
    {
        $filePath = 'MyCatalogId/testSymbolicLinking/html/test.txt';
        // Ensure that our file and directory don't exist.
        $this->assertFileDoesNotExist($this->archiveBase.'/'.$filePath);
        $this->assertFalse($this->archiveStorage->exists($filePath));

        // Create the file.
        $archiveFile = $this->archiveStorage->writeFile($filePath, 'Hello world.');
        $this->assertEquals($filePath, $archiveFile->path());

        $linkPath = 'MyCatalogId/testSymbolicLinking/link.txt';
        $this->assertFileDoesNotExist($this->archiveBase.'/'.$linkPath);
        $this->assertFalse($this->archiveStorage->exists($linkPath));

        // Make the symbolic link.
        $link = $this->archiveStorage->makeLink($linkPath, 'html/test.txt');
        // Verify that our file was moved.
        $this->assertFileExists($this->archiveBase.'/'.$linkPath);
        $this->assertTrue($this->archiveStorage->exists($linkPath));

        $link = $this->archiveStorage->get($linkPath);
        $this->assertEquals('html/test.txt', $link->getTarget());
        $this->assertEquals('Hello world.', $link->getFileContent());

        // Write a second file and then update our link to point at it.
        $filePath2 = 'MyCatalogId/testSymbolicLinking/html/test_2.txt';
        $this->archiveStorage->writeFile($filePath2, 'Hello 2nd world.');
        $link = $this->archiveStorage->makeLink($linkPath, 'html/test_2.txt');
        // verify that the link was moved.
        $this->assertEquals('html/test_2.txt', $link->getTarget());
        $this->assertEquals('Hello 2nd world.', $link->getFileContent());
    }

    public function testWriteEscapeBaseDirAbsolute(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Path cannot be absolute, it must be relative.');
        $archiveFile = $this->archiveStorage->writeFile('/tmp/test', 'Hello world.');
    }

    public function testWriteEscapeBaseDirRelative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Requested path is not relative to our archive directory.');
        $archiveFile = $this->archiveStorage->writeFile('MyCatalog/../../test.txt', 'Hello world.');
    }

    public function testDeleteEscapeBaseDirAbsolute(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Path cannot be absolute, it must be relative.');
        $this->archiveStorage->delete('/tmp');
    }

    public function testDeleteEscapeBaseDirRelative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Requested path is not relative to our archive directory.');
        $this->archiveStorage->delete('MyCatalog/../../test.txt');
    }

    public function testLinkEscapeBaseDirAbsolute(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Path cannot be absolute, it must be relative.');
        $archiveLink = $this->archiveStorage->makeLink('/test', 'bin/sh');
    }

    public function testLinkEscapeBaseDirRelative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Requested path is not relative to our archive directory.');
        $archiveLink = $this->archiveStorage->makeLink('MyCatalog/../../tmp', 'bin/sh');
    }

    public function testLinkTargetEscapeBaseDirAbsolute(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Target path cannot be absolute, it must be relative.');
        $archiveLink = $this->archiveStorage->makeLink('targettest', '/bin/sh');
    }

    public function testLinkTargetEscapeBaseDirRelative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Target path is not relative to our archive directory.');
        $archiveLink = $this->archiveStorage->makeLink('targettest2', Path::makeRelative('/bin/sh', $this->archiveBase));
    }

    public function testRenameEscapeBaseDirOriginalAbsolute(): void
    {
        $projectDir = static::getContainer()->getParameter('kernel.project_dir');
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Path cannot be absolute, it must be relative.');
        $this->archiveStorage->rename($projectDir.'/README.md', 'test.txt');
    }

    public function testRenameEscapeBaseDirOriginalRelative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Requested path is not relative to our archive directory.');
        $this->archiveStorage->rename('../../../README.md', 'test-readme.txt');
    }

    public function testRenameEscapeBaseDirNewAbsolute(): void
    {
        $archiveFile = $this->archiveStorage->writeFile('test.txt', 'Hello world.');
        $projectDir = static::getContainer()->getParameter('kernel.project_dir');
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Path cannot be absolute, it must be relative.');
        $this->archiveStorage->rename('test.txt', $projectDir.'/test.txt');
    }

    public function testRenameEscapeBaseDirNewRelative(): void
    {
        $archiveFile = $this->archiveStorage->writeFile('test.txt', 'Hello world.');
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Requested path is not relative to our archive directory.');
        $this->archiveStorage->rename('test.txt', '../../../test.txt');
    }
}
