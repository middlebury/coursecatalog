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
        } else {
            return new ArchiveFile($this->basePath, $path);
        }
    }
}
