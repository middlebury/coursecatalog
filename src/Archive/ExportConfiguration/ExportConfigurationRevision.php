<?php

namespace App\Archive\ExportConfiguration;

/**
 * An Export configuration revision instance.
 */
class ExportConfigurationRevision
{
    public function __construct(
        protected int $id,
        protected array $content,
        protected string $note,
        protected \DateTime $timestamp,
        protected string $userId,
        protected string $userDisplayName,
    ) {
    }

    /**
     * Answer the id of this configuration revision.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Answer the content of this configuration revision.
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * Answer the content of this configuration revision as a JSON string.
     */
    public function getJson(): string
    {
        return json_encode($this->content, \JSON_PRETTY_PRINT);
    }

    /**
     * Answer the note of this configuration revision.
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * Answer the timestamp of this configuration revision.
     *
     * @return DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * Answer the id of the user who created this configuration revision.
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * Answer the name of the user who created this configuration revision.
     */
    public function getUserDisplayName(): string
    {
        return $this->userDisplayName;
    }
}
