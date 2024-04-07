<?php

namespace Valres\SanctionsSystem\managers\discord;

use Valres\SanctionsSystem\libs\DiscordWebhookAPI\Embed;
use Valres\SanctionsSystem\SanctionsSystem;

class DiscordManager
{
    private string $banWebhook = "";
    private string $muteWebhook = "";
    private string $kickWebhook = "";
    private Embed $banEmbed;
    private Embed $muteEmbed;
    private Embed $kickEmbed;

    public function init(): void
    {
        $config = SanctionsSystem::getInstance()->getConfig();

        $this->banWebhook = $config->get("ban-webhook")["url"];
        $this->initBanEmbed();

        $this->muteWebhook = $config->get("mute-webhook")["url"];
        $this->initMuteEmbed();

        $this->kickWebhook = $config->get("kick-webhook")["url"];
        $this->initKickEmbed();

    }

    public function initBanEmbed(): void
    {
        $config = SanctionsSystem::getInstance()->getConfig();

        $embed = new Embed();
        $embed->setTitle($config->get("ban-webhook")["embed-title"]);
        $embed->setFooter("Plugin by ValresMC.");
        $this->banEmbed = $embed;
    }

    public function initMuteEmbed(): void
    {
        $config = SanctionsSystem::getInstance()->getConfig();

        $embed = new Embed();
        $embed->setTitle($config->get("mute-webhook")["embed-title"]);
        $embed->setFooter("Plugin by ValresMC.");
        $this->muteEmbed = $embed;
    }

    public function initKickEmbed(): void
    {
        $config = SanctionsSystem::getInstance()->getConfig();

        $embed = new Embed();
        $embed->setTitle($config->get("kick-webhook")["embed-title"]);
        $embed->setFooter("Plugin by ValresMC.");
        $this->kickEmbed = $embed;
    }

    /**
     * @return Embed
     */
    public function getBanEmbed(): Embed
    {
        return $this->banEmbed;
    }

    /**
     * @return Embed
     */
    public function getMuteEmbed(): Embed
    {
        return $this->muteEmbed;
    }

    /**
     * @return Embed
     */
    public function getKickEmbed(): Embed
    {
        return $this->kickEmbed;
    }

    /**
     * @return string
     */
    public function getBanWebhook(): string
    {
        return $this->banWebhook;
    }

    /**
     * @return string
     */
    public function getMuteWebhook(): string
    {
        return $this->muteWebhook;
    }

    /**
     * @return string
     */
    public function getKickWebhook(): string
    {
        return $this->kickWebhook;
    }
}
