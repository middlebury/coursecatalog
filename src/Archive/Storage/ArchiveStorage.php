<?php

namespace App\Archive\Storage;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

/**
 * Provides access to archives.
 */
class ArchiveStorage
{
    public function __construct(
        private string $basePath,
        private Filesystem $filesystem,
        #[Autowire('%kernel.project_dir%')]
        private $projectDir,
    ) {
        // Trim off any trailing slash.
        $basePath = rtrim($basePath, '/');
        if (empty($basePath)) {
            throw new \InvalidArgumentException('$basePath must be configured.');
        }
        if ($filesystem->isAbsolutePath($basePath)) {
            $this->basePath = $basePath;
        } else {
            $this->basePath = $projectDir.'/'.$basePath;
        }
    }

    /**
     * Answer an Archive directory or file.
     *
     * @param string $path
     *                     The directory or file path
     *
     * @return archiveItemIterface
     *                             The directory or file
     */
    public function get(string $path = ''): ArchiveItemIterface
    {
        // Trim off any trailing slash.
        $path = rtrim($path, '/');

        if (!Path::isBasePath($this->basePath, $this->basePath.'/'.$path)) {
            throw new \InvalidArgumentException('Requested path is not relative to our archive directory.');
        }
        if (!$this->filesystem->exists($this->basePath.'/'.$path)) {
            throw new \InvalidArgumentException('Unknown path: '.$path);
        }
        if (is_dir($this->basePath.'/'.$path)) {
            return new ArchiveDirectory($this->basePath, $path);
        } elseif (is_link($this->basePath.'/'.$path)) {
            return new ArchiveLink($this->basePath, $path);
        } else {
            return new ArchiveFile($this->basePath, $path);
        }
    }

    /**
     * Create or overwrite an ArchiveFile.
     *
     * @param string $path
     *                        The relative path to the file
     * @param string $content
     *                        File content to be written to the file
     *
     * @return archiveFile
     *                     The newly created file
     */
    public function writeFile(string $path, string $content = '')
    {
        $dir = dirname($path);
        if (!$this->filesystem->exists($this->basePath.'/'.$dir)) {
            $this->filesystem->mkdir($this->basePath.'/'.$dir);
        }
        $this->filesystem->dumpFile($this->basePath.'/'.$path, $content);

        return $this->get($path);
    }

    /**
     * Answer true if a a file, link, or directory exists at a path.
     *
     * @param string $path
     */
    public function exists($path)
    {
        clearstatcache(true);
        if (!Path::isBasePath($this->basePath, $this->basePath.'/'.$path)) {
            throw new \InvalidArgumentException('Requested path is not relative to our archive directory.');
        }

        return file_exists($this->basePath.'/'.$path);
    }

    /**
     * Move this file to a new path.
     */
    public function rename(string $oldPath, string $newPath, bool $overwrite = false)
    {
        if (!$this->exists($oldPath)) {
            throw new \InvalidArgumentException('$oldPath does not exist.');
        }
        // Make sure that the new parent directory exists.
        $dir = dirname($newPath);
        if (!$this->filesystem->exists($this->basePath.'/'.$dir)) {
            $this->filesystem->mkdir($this->basePath.'/'.$dir);
        }

        $this->filesystem->rename($this->basePath.'/'.$oldPath, $this->basePath.'/'.$newPath, $overwrite);
    }

    /**
     * Delete an item.
     *
     * @param string $path
     */
    public function delete($path)
    {
        if (!Path::isBasePath($this->basePath, $this->basePath.'/'.$path)) {
            throw new \InvalidArgumentException('Requested path is not relative to our archive directory.');
        }
        if ($this->exists($path)) {
            $this->filesystem->remove($this->basePath.'/'.$path);
        }
    }

    /**
     * Create or overwrite an ArchiveLink.
     *
     * @param string $path
     *                           The relative path of the link
     * @param string $targetPath
     *                           The target path relative to the link path
     */
    public function makeLink(string $path, string $targetPath)
    {
        $this->delete($path);
        // Create the new link as a relative symbolic link for filesystem
        // portability.
        $cwd = getcwd();
        chdir(realpath(dirname($this->basePath.'/'.$path)));
        clearstatcache(true);
        $command = 'ln -s '.escapeshellarg($targetPath).' '.escapeshellarg($this->basePath.'/'.$path);
        echo "$command \n";
        if (!exec($command)) {
            chdir($cwd);

            return $this->get($path);
        // }
        // if (@symlink($targetPath, basename($path))) {
        // chdir($cwd);
        //     return $this->get($path);
        } else {
            chdir($cwd);
            throw new \Exception("Failed to create symlink at $path pointing at $targetPath)");
        }
    }
}
