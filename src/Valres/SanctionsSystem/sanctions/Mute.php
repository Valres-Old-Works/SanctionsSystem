<?php

namespace Valres\SanctionsSystem\sanctions;

class Mute
{
    public function __construct(
        public string $playerName,
        public string $reason,
        public int $time,
        public string $authorName
    ) {}

    /**
     * @return string
     */
    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }
}