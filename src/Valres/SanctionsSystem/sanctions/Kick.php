<?php

namespace Valres\SanctionsSystem\sanctions;

class Kick
{
    public function __construct(
        public string $playerName,
        public string $reason,
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
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }
}