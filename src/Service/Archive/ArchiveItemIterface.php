<?php

namespace App\Service\Archive;

interface ArchiveItemIterface
{
    /**
     * Answer the filename of the item.
     */
    public function basename(): string;

    /**
     * Answer the path of the item relative to the archive base.
     */
    public function path(): string;

    /**
     * Answer true if the item is a file (not a directory).
     */
    public function isFile(): bool;

    /**
     * Answer true if the item is a directory.
     */
    public function isDir(): bool;
}
