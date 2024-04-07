<?php

namespace Valres\SanctionsSystem\commands;

use JsonException;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use Valres\SanctionsSystem\libs\CortexPE\Commando\args\RawStringArgument;
use Valres\SanctionsSystem\libs\CortexPE\Commando\BaseCommand;
use Valres\SanctionsSystem\libs\CortexPE\Commando\exception\ArgumentOrderException;
use Valres\SanctionsSystem\SanctionsSystem;
use Valres\SanctionsSystem\utils\TimeHelper;

class MuteCommand extends BaseCommand
{
    /**
     * @return void
     * @throws ArgumentOrderException
     */
    protected function prepare(): void
    {
        $this->setPermission("sanctionssystem.mute.command");
        $this->registerArgument(0, new RawStringArgument("player", false));
        $this->registerArgument(1, new RawStringArgument("time", false));
        $this->registerArgument(2, new RawStringArgument("reason", false));
    }

    /**
     * @param CommandSender $sender
     * @param string $aliasUsed
     * @param array $args
     * @return void
     */
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

        if($sanctionsManager->isMuted($player)){
            $sender->sendMessage($config->get("already-mute-message"));
            return;
        }

        $sanctionsManager->addMute($player, $reason, $time, $sender->getName());
        Server::getInstance()->broadcastMessage(str_replace(
            ["{player}", "{reason}", "{time}", "{author}"],
            [$player, $reason, $time, $sender->getName()],
            $config->get("mute-broadcast-message")
        ));
        SanctionsSystem::getInstance()->discordManager->sendMuteEmbed($player, $reason, $time, $sender->getName());
    }

    public function getPermission() {}
}

