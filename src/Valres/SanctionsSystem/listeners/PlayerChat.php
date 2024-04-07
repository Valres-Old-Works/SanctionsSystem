<?php

namespace Valres\SanctionsSystem\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use Valres\SanctionsSystem\SanctionsSystem;
use Valres\SanctionsSystem\utils\TimeHelper;

class PlayerChat implements Listener
{
    /**
     * @param PlayerChatEvent $event
     * @return void
     */
    public function onChat(PlayerChatEvent $event): void
    {
        $player = $event->getPlayer();
        $playerName = $player->getName();
        $sanctionsManager = SanctionsSystem::getInstance()->sanctionsManager;
        $config = SanctionsSystem::getInstance()->getConfig();

        if($sanctionsManager->isMuted($playerName)){
            $event->cancel();
            $mute = $sanctionsManager->getMute($playerName);
            $player->sendMessage(str_replace(
                ["{reason}", "{time}", "{author}"],
                [$mute->getReason(), TimeHelper::timeToString($mute->getTime()), $mute->getAuthorName()],
                $config->get("mute-message")
            ));
        }

    }
}
