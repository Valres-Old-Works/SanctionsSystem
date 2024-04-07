<?php

namespace Valres\SanctionsSystem\commands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use Valres\SanctionsSystem\libs\CortexPE\Commando\args\RawStringArgument;
use Valres\SanctionsSystem\libs\CortexPE\Commando\BaseCommand;
use Valres\SanctionsSystem\libs\CortexPE\Commando\exception\ArgumentOrderException;
use Valres\SanctionsSystem\SanctionsSystem;
use Valres\SanctionsSystem\utils\TimeHelper;

class BanCommand extends BaseCommand
{
    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->setPermission("sanctionssystem.ban.command");
        $this->registerArgument(0, new RawStringArgument("player", false));
        $this->registerArgument(1, new RawStringArgument("time", false));
        $this->registerArgument(2, new RawStringArgument("reason", false));

    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $sanctionsManager = SanctionsSystem::getInstance()->sanctionsManager;
        $config = SanctionsSystem::getInstance()->getConfig();

        $player = $args["player"];

        $unit = substr($args["time"], -1);
        if(array_key_exists($unit, TimeHelper::$timeUnits)){
            $time = time() + ((int)$args["time"] * TimeHelper::$timeUnits[$unit]);
        } else {
            $sender->sendMessage("§cInvalid time format.");
            return;
        }

        $reason = $args["reason"];

        if($sanctionsManager->isBanned($player)){
            $sender->sendMessage($config->get("already-ban-message"));
            return;
        }

        $sanctionsManager->addBan($player, $reason, $time, $sender->getName());
        Server::getInstance()->broadcastMessage(str_replace(
            ["{player}", "{reason}", "{time}", "{author}"],
            [$player, $reason, $time, $sender->getName()],
            $config->get("ban-broadcast-message")
        ));
        if(Server::getInstance()->getPlayerExact($player) instanceof Player){
            Server::getInstance()->getPlayerExact($player)->kick(str_replace(
                ["{reason}", "{remaining}", "{author}"],
                [$reason, TimeHelper::timeToString($time), $sender->getName()],
                $config->get("ban-message")
            ));
        }
    }

    public function getPermission() {}
}
