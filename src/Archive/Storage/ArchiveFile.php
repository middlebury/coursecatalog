<?php

namespace App\Archive\Storage;

class ArchiveFile implements ArchiveFileInterface
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
     * Answer the filesystem path of the file.
     */
    public function realPath(): string
    {
        return $this->basePath.'/'.$this->relativePath;
    }

    /**
     * Answer true if the item is a file (not a directory).
     */
    public function isFile(): bool
    {
        return true;
    }

    /**
     * Answer true if the item is a directory.
     */
    public function isDir(): bool
    {
        return false;
    }

    /**
     * Answer the content of the file.
     */
    public function getFileContent(): string
    {
        return file_get_contents($this->basePath.'/'.$this->relativePath);
    }

    /**
     * Answer the title from the HTML of an archive file.
     */
    public function getTitle(): string
    {
        $doc = $this->getDomDocument();
        $xpath = new \DOMXPath($doc);

        return $xpath->query('/html/head/title')->item(0)->nodeValue;
    }

    /**
     * Answer the body HTML of an archive file.
     */
    public function getBodyHtml(): string
    {
        $bodyHtml = '';
        $doc = $this->getDomDocument();
        $xpath = new \DOMXPath($doc);
        foreach ($xpath->query('/html/body')->item(0)->childNodes as $node) {
            $bodyHtml .= $doc->saveHTML($node);
        }

        return $bodyHtml;
    }

    protected $doc;

    /**
     * Answer a DOMDocument for our content.
     *
     * @return DOMDocument
     */
    protected function getDomDocument(): \DOMDocument
    {
        if (!isset($this->doc)) {
            $doc = new \DOMDocument();
            libxml_use_internal_errors(true); // Don't print errors related to HTML5 enties.
            $doc->loadHTML($this->getFileContent());
            libxml_use_internal_errors(false);
            $this->doc = $doc;
        }

        return $this->doc;
    }
}
