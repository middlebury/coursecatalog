<?php

namespace App\Archive\Storage;

interface ArchiveFileInterface extends ArchiveItemIterface
{
    /**
     * Answer the content of the file.
     */
    public function getFileContent(): string;

    /**
     * Answer the title from the HTML of an archive file.
     */
    public function getTitle(): string;

    /**
     * Answer the body HTML of an archive file.
     */
    public function getBodyHtml(): string;
}
