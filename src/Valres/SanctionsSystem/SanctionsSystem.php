<?php

namespace Valres\SanctionsSystem;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use Valres\SanctionsSystem\listeners\PlayerChat;
use Valres\SanctionsSystem\listeners\PlayerJoin;
use Valres\SanctionsSystem\managers\discord\DiscordManager;
use Valres\SanctionsSystem\managers\SanctionsManager;

class SanctionsSystem extends PluginBase
{
    public DiscordManager $discordManager;
    public SanctionsManager $sanctionsManager;

    use SingletonTrait;

    protected function onEnable(): void
    {
        $this->saveDefaultConfig();
        @mkdir($this->getDataFolder() . "sanctions/");
        $this->saveResource("sanctions/bans.yml");
        $this->saveResource("sanctions/mutes.yml");


        $this->discordManager = new DiscordManager();
        $this->discordManager->init();

        $this->sanctionsManager = new SanctionsManager();

        $this->getServer()->getPluginManager()->registerEvents(new PlayerChat(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerJoin(), $this);
    }

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    /**
     * @return Config
     */
    public function getBanDatas(): Config
    {
        return new Config($this->getDataFolder() . "sanctions/bans.yml", Config::YAML);
    }

    /**
     * @return Config
     */
    public function getMuteDatas(): Config
    {
        return new Config($this->getDataFolder() . "sanctions/mutes.yml", Config::YAML);
    }
}
