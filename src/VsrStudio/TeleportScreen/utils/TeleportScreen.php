<?php

namespace VsrStudio\TeleportScreen\utils;

use VsrStudio\TeleportScreen\Loader;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class TeleportScreen {

    /**
     * Show teleport screen and play sound (if set)
     *
     * @param Player $player
     * @param bool $sound
     * @return void
     */
    public static function screen(Player $player, bool $sound = true): void {
        $player->sendTitle("teleport_screen");

        if ($sound) {
            $soundName = self::getConfiguredSound();
            if ($soundName !== "") {
                $pos = $player->getPosition();
                $packet = PlaySoundPacket::create($soundName, $pos->getX(), $pos->getY(), $pos->getZ(), 1.0, 1.0);
                $player->getNetworkSession()->sendDataPacket($packet);
            }
        }
    }

    /**
     * Close teleport screen
     *
     * @param Player $player
     * @return void
     */
    public static function close(Player $player): void {
        $player->removeTitles();
    }

    /**
     * Get the sound name from config.yml
     *
     * @return string
     */
    private static function getConfiguredSound(): string {
        return (string) Loader::getInstance()->getPluginConfig()->get("screen-sound", "mob.enderdragon.hit");
    }
}
