<?php

namespace Valres\SanctionsSystem\commands;

use JsonException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use Valres\SanctionsSystem\libs\CortexPE\Commando\args\RawStringArgument;
use Valres\SanctionsSystem\libs\CortexPE\Commando\BaseCommand;
use Valres\SanctionsSystem\libs\CortexPE\Commando\exception\ArgumentOrderException;
use Valres\SanctionsSystem\SanctionsSystem;

class KickCommand extends BaseCommand
{
    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->setPermission("sanctionssystem.kick.command");
        $this->registerArgument(0, new RawStringArgument("player", false));
        $this->registerArgument(1, new RawStringArgument("reason", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $config = SanctionsSystem::getInstance()->getConfig();

        $player = $args["player"];

        $reason = $args["reason"];

        if(!Server::getInstance()->getPlayerExact($player) instanceof Player){
            $sender->sendMessage($config->get("not-connected"));
            return;
        }

        Server::getInstance()->getPlayerExact($player)->kick(str_replace(
            ["{reason}", "{author}"],
            [$reason, $sender->getName()],
            $config->get("kick-message")
        ));
        Server::getInstance()->broadcastMessage(str_replace(
            ["{player}", "{reason}", "{author}"],
            [$player, $reason, $sender->getName()],
            $config->get("kick-broadcast-message")
        ));
        SanctionsSystem::getInstance()->discordManager->sendKickEmbed($player, $reason, $sender->getName());

    }

    public function getPermission() {}
}
