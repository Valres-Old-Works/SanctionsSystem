<?php

namespace Valres\SanctionsSystem\commands;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use Valres\SanctionsSystem\libs\CortexPE\Commando\args\RawStringArgument;
use Valres\SanctionsSystem\libs\CortexPE\Commando\BaseCommand;
use Valres\SanctionsSystem\libs\CortexPE\Commando\exception\ArgumentOrderException;
use Valres\SanctionsSystem\SanctionsSystem;

class UnbanCommand extends BaseCommand
{
    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->setPermission("sanctionssystem.unban.command");
        $this->registerArgument(0, new RawStringArgument("player", false));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $sanctionsManager = SanctionsSystem::getInstance()->sanctionsManager;
        $config = SanctionsSystem::getInstance()->getConfig();

        $player = $args["player"];

        if(!$sanctionsManager->isBanned($player)){
            $sender->sendMessage($config->get("not-ban-message"));
            return;
        }

        $sanctionsManager->deleteBan($player);
        Server::getInstance()->broadcastMessage(str_replace(
            ["{player}", "author"],
            [$player, $sender->getName()],
            $config->get("unban-broadcast-message")
        ));
    }

    public function getPermission() {}
}
