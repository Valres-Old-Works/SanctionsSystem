<?php

namespace Valres\SanctionsSystem\managers\discord;

use Valres\SanctionsSystem\libs\DiscordWebhookAPI\Embed;
use Valres\SanctionsSystem\libs\DiscordWebhookAPI\Message;
use Valres\SanctionsSystem\libs\DiscordWebhookAPI\Webhook;
use Valres\SanctionsSystem\SanctionsSystem;

class DiscordManager
{
    private string $banWebhook = "";
    private string $muteWebhook = "";
    private string $kickWebhook = "";

    public function init(): void
    {
        $config = SanctionsSystem::getInstance()->getConfig();

        $this->banWebhook = $config->get("ban-webhook")["url"];
        $this->muteWebhook = $config->get("mute-webhook")["url"];
        $this->kickWebhook = $config->get("kick-webhook")["url"];

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

    /**
     * @param string $playerName
     * @param string $reason
     * @param string $time
     * @param string $author
     * @return void
     */
    public function sendBanEmbed(string $playerName, string $reason, string $time, string $author): void
    {
        $config = SanctionsSystem::getInstance()->getConfig();

        if($this->banWebhook === "") return;

        $embed = new Embed();
        $embed->setTitle($config->get("ban-webhook")["title"]);
        $embed->setDescription(str_replace(
            ["{player}", "{reason}", "{time}", "{author}"],
            [$playerName, $reason, $time, $author],
            $config->get("ban-webhook")["content"]
        ));

        $webhook = new Webhook($this->banWebhook);
        $message = new Message();
        $message->addEmbed($embed);
        $webhook->send($message);
    }

    /**
     * @param string $playerName
     * @param string $reason
     * @param string $time
     * @param string $author
     * @return void
     */
    public function sendMuteEmbed(string $playerName, string $reason, string $time, string $author): void
    {
        $config = SanctionsSystem::getInstance()->getConfig();

        if($this->muteWebhook === "") return;

        $embed = new Embed();
        $embed->setTitle($config->get("mute-webhook")["title"]);
        $embed->setDescription(str_replace(
            ["{player}", "{reason}", "{time}", "{author}"],
            [$playerName, $reason, $time, $author],
            $config->get("mute-webhook")["content"]
        ));

        $webhook = new Webhook($this->muteWebhook);
        $message = new Message();
        $message->addEmbed($embed);
        $webhook->send($message);
    }

    /**
     * @param string $playerName
     * @param string $reason
     * @param string $author
     * @return void
     */
    public function sendKickEmbed(string $playerName, string $reason, string $author): void
    {
        $config = SanctionsSystem::getInstance()->getConfig();

        if($this->kickWebhook === "") return;

        $embed = new Embed();
        $embed->setTitle($config->get("kick-webhook")["title"]);
        $embed->setDescription(str_replace(
            ["{player}", "{reason}", "{author}"],
            [$playerName, $reason, $author],
            $config->get("kick-webhook")["content"]
        ));

        $webhook = new Webhook($this->kickWebhook);
        $message = new Message();
        $message->addEmbed($embed);
        $webhook->send($message);
    }
}
