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
        $this->checkPathInBase($path);

        // Trim off any trailing slash.
        $path = rtrim($path, '/');

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
        $this->checkPathInBase($path);

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
        $this->checkPathInBase($path);

        // Clear the filesystem cache for this path before checking that it exists.
        clearstatcache(true, $this->basePath.'/'.$path);

        return file_exists($this->basePath.'/'.$path);
    }

    /**
     * Move this file to a new path.
     */
    public function rename(string $oldPath, string $newPath, bool $overwrite = false)
    {
        $this->checkPathInBase($oldPath);
        $this->checkPathInBase($newPath);

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
        $this->checkPathInBase($path);

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
    public function makeLink(string $path, string $targetPath): ArchiveLinkInterface
    {
        $this->checkPathInBase($path);

        $this->delete($path);
        // Create the new link as a relative symbolic link for filesystem
        // portability if the base directory is changed.
        $cwd = getcwd();
        // Change to the working directory where the link object will live.
        chdir(realpath(dirname($this->basePath.'/'.$path)));

        // Verify that our target exists and isn't outside of our basePath.
        if ($this->filesystem->isAbsolutePath($targetPath)) {
            throw new \InvalidArgumentException('Target path cannot be absolute, it must be relative.');
        }
        if (!Path::isBasePath($this->basePath, getcwd().'/'.$targetPath)) {
            chdir($cwd);
            throw new \InvalidArgumentException('Target path is not relative to our archive directory.');
        }
        if (!file_exists($targetPath)) {
            chdir($cwd);
            throw new \InvalidArgumentException("target file doesn't exist at $targetPath");
        }
        // Make the relative symbolic link.
        if (symlink($targetPath, basename($path))) {
            chdir($cwd);

            return $this->get($path);
        } else {
            chdir($cwd);
            throw new \Exception("Failed to create symlink at $path pointing at $targetPath)");
        }
    }

    /**
     * Check that the path passed is relative and a child of our base directory.
     *
     * Throws an InvalidArgumentException if the path is not a proper sub-path
     * of our base directory.
     */
    protected function checkPathInBase(string $path): void
    {
        if ($this->filesystem->isAbsolutePath($path)) {
            throw new \InvalidArgumentException('Path cannot be absolute, it must be relative.');
        }
        if (!Path::isBasePath($this->basePath, $this->basePath.'/'.$path)) {
            throw new \InvalidArgumentException('Requested path is not relative to our archive directory.');
        }
    }
}
