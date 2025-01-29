<?php

namespace App\Service\Archive;

class ArchiveDirectory implements ArchiveDirectoryInterface
{
    public function __construct(
        protected string $basePath,
        protected string $relativePath,
    ) {
    }

    /**
     * Answer the filename of the item.
     */
    public function basename(): string
    {
        return basename($this->relativePath);
    }

    /**
     * Answer the path of the item relative to the archive base.
     */
    public function path(): string
    {
        return $this->relativePath;
    }

    /**
     * Answer true if the item is a file (not a directory).
     */
    public function isFile(): bool
    {
        return false;
    }

    /**
     * Answer true if the item is a directory.
     */
    public function isDir(): bool
    {
        return true;
    }

    /**
     * Answer the child items in the directory.
     *
     * @return array<ArchiveItemIterface>
     */
    public function children(): array
    {
        $children = [];
        foreach (scandir($this->basePath.'/'.$this->relativePath) as $child) {
            if ('.' != $child && '..' != $child) {
                $childPath = $this->relativePath ? $this->relativePath.'/'.$child : $child;
                if (is_dir($this->basePath.'/'.$childPath)) {
                    $children[] = new ArchiveDirectory($this->basePath, $childPath);
                } else {
                    $children[] = new ArchiveFile($this->basePath, $childPath);
                }
            }
        }

        return $children;
    }
}
