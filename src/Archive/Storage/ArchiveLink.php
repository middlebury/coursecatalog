<?php

namespace App\Archive\Storage;

class ArchiveLink extends ArchiveFile implements ArchiveLinkInterface
{
    /**
     * Answer the relative target path of a link.
     */
    public function getTarget(): string
    {
        return readlink($this->realPath());
    }
}
