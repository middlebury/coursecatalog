<?php

namespace App\Archive\Storage;

interface ArchiveDirectoryInterface extends ArchiveItemIterface
{
    /**
     * Answer the child items in the directory.
     *
     * @return array<ArchiveItemIterface>
     */
    public function children(): array;
}
