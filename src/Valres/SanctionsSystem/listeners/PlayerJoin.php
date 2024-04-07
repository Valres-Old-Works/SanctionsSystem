<?php

namespace Valres\SanctionsSystem\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use Valres\SanctionsSystem\SanctionsSystem;
use Valres\SanctionsSystem\utils\TimeHelper;

class PlayerJoin implements Listener
{
    /**
     * @param PlayerPreLoginEvent $event
     * @return void
     */
    public function onPreLogin(PlayerPreLoginEvent $event): void
    {
        $playerName = $event->getPlayerInfo()->getUsername();
        $sanctionsManager = SanctionsSystem::getInstance()->sanctionsManager;
        $config = SanctionsSystem::getInstance()->getConfig();

        if($sanctionsManager->isBanned($playerName)){
            $ban = $sanctionsManager->getBan($playerName);
            if($ban->getTime() - time() <= 0){
                $sanctionsManager->deleteBan($playerName);
            }

            $event->setKickFlag(PlayerPreLoginEvent::KICK_FLAG_BANNED, str_replace(
                ["{reason}", "{time}", "{author}"],
                [$ban->getReason(), TimeHelper::timeToString($ban->getTime()), $ban->getAuthorName()],
                $config->get("ban-message")
            ));
        }
    }
}
