<?php

namespace VsrStudio\TeleportScreen\event;

use VsrStudio\TeleportScreen\Loader;
use VsrStudio\TeleportScreen\utils\TeleportScreen;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat as C;

class MoveEvent implements Listener {

    /** @var array<string, int> freeze end time per player */
    private array $screenedPlayers = [];

    /**
     * Show screen and freeze if set
     */
    private function handleScreen(Player $player): void {
        $config = Loader::getInstance()->getPluginConfig();
        $delay = (int)$config->get("screen-delay", 5);
        $message = C::colorize($config->get("screen-message", "§bTeleporting..."));
        $freeze = (bool)$config->get("screen-freeze", true);

        $id = spl_object_hash($player);

        if (isset($this->screenedPlayers[$id])) return;

        $this->screenedPlayers[$id] = time() + $delay;

        TeleportScreen::screen($player);
        $player->sendTip($message);

        Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($player, $id): void {
            if ($player->isOnline()) {
                TeleportScreen::close($player);
            }
            unset($this->screenedPlayers[$id]);
        }), $delay * 20);
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if (Loader::getInstance()->getPluginConfig()->get("screen-settings")["join"]) {
            $this->handleScreen($player);
        }
    }

    public function onRespawn(PlayerRespawnEvent $event): void {
        $player = $event->getPlayer();
        if (Loader::getInstance()->getPluginConfig()->get("screen-settings")["respawn"]) {
            $this->handleScreen($player);
        }
    }

    public function onTeleport(EntityTeleportEvent $event): void {
        $entity = $event->getEntity();
        if (!$entity instanceof Player) return;

        $from = $event->getFrom()->getWorld();
        $to = $event->getTo()->getWorld();

        if ($from->getFolderName() === $to->getFolderName()) return;

        if (Loader::getInstance()->getPluginConfig()->get("screen-settings")["teleport"]) {
            $this->handleScreen($entity);
        }
    }

    public function onQuit(PlayerQuitEvent $event): void {
        $id = spl_object_hash($event->getPlayer());
        unset($this->screenedPlayers[$id]);
    }

    /**
     * Prevent movement of players who are currently frozen
     */
    public function onMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();
        $id = spl_object_hash($player);
        $config = Loader::getInstance()->getPluginConfig();
        $freeze = (bool)$config->get("screen-freeze", true);

        if ($freeze && isset($this->screenedPlayers[$id])) {
            if (time() < $this->screenedPlayers[$id]) {
                $event->cancel();
            }
        }
    }
}
