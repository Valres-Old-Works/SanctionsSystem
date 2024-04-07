<?php

namespace Valres\SanctionsSystem\managers;

use JsonException;
use Valres\SanctionsSystem\sanctions\Ban;
use Valres\SanctionsSystem\sanctions\Mute;
use Valres\SanctionsSystem\SanctionsSystem;

class SanctionsManager
{
    /** @var Ban[] */
    private array $bans = [];

    /** @var Mute[] */
    private array $mutes = [];

    /**
     * @param string $playerName
     * @return Ban|null
     */
    public function getBan(string $playerName): ?Ban
    {
        return $this->bans[$playerName] ?? null;
    }

    /**
     * @param string $playerName
     * @return Mute|null
     */
    public function getMute(string $playerName): ?Mute
    {
        return $this->mutes[$playerName] ?? null;
    }

    /**
     * @param string $playerName
     * @return bool
     */
    public function isBanned(string $playerName): bool
    {
        return array_key_exists($playerName, $this->bans);
    }

    /**
     * @param string $playerName
     * @return bool
     */
    public function isMuted(string $playerName): bool
    {
        return array_key_exists($playerName, $this->bans);
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function loadBans(): void
    {
        $bans = SanctionsSystem::getInstance()->getBanDatas();

        foreach($bans->getAll() as $playerName => ["reason" => $reason, "time" => $time, "author" => $authorName]){
            if($time - time() <= 0){
                $bans->remove($playerName);
                $bans->save();
                return;
            }

            $this->addBan($playerName, $reason, $time, $authorName);
        }
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function saveBans(): void
    {
        $bans = SanctionsSystem::getInstance()->getBanDatas();
        $bans->setAll([]);

        foreach($this->bans as $ban){
            $bans->set($ban->getPlayerName(), [
                "reason" => $ban->getReason(),
                "time" => $ban->getTime(),
                "author" => $ban->getAuthorName()
            ]);
        }
        $bans->save();
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function loadMutes(): void
    {
        $mutes = SanctionsSystem::getInstance()->getMuteDatas();

        foreach($mutes->getAll() as $playerName => ["reason" => $reason, "time" => $time, "author" => $authorName]){
            if($time - time() <= 0){
                $mutes->remove($playerName);
                $mutes->save();
                return;
            }

            $this->addMute($playerName, $reason, $time, $authorName);
        }
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function saveMutes(): void
    {
        $mutes = SanctionsSystem::getInstance()->getMuteDatas();
        $mutes->setAll([]);

        foreach($this->mutes as $mute){
            $mutes->set($mute->getPlayerName(), [
                "reason" => $mute->getReason(),
                "time" => $mute->getTime(),
                "author" => $mute->getAuthorName()
            ]);
        }
        $mutes->save();
    }

    /**
     * @param string $playerName
     * @param string $reason
     * @param int $time
     * @param string $author
     * @return void
     */
    public function addBan(string $playerName, string $reason, int $time, string $author): void
    {
        $this->bans[$playerName] = new Ban($playerName, $reason, $time, $author);
    }

    /**
     * @param string $playerName
     * @return void
     */
    public function deleteBan(string $playerName): void
    {
        unset($this->bans[$playerName]);
    }

    /**
     * @param string $playerName
     * @param string $reason
     * @param int $time
     * @param string $author
     * @return void
     */
    public function addMute(string $playerName, string $reason, int $time, string $author): void
    {
        $this->mutes[$playerName] = new Mute($playerName, $reason, $time, $author);
    }

    /**
     * @param string $playerName
     * @return void
     */
    public function deleteMute(string $playerName): void
    {
        unset($this->mutes[$playerName]);
    }
}
