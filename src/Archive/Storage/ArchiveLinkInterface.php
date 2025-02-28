<?php

namespace App\Archive\Storage;

interface ArchiveLinkInterface extends ArchiveFileInterface
{
    /**
     * Answer the relative target path of a link.
     */
    public function getTarget(): string;
}
