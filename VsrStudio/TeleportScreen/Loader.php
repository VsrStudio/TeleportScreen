<?php

namespace VsrStudio\TeleportScreen;

use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use VsrStudio\TeleportScreen\event\MoveEvent;
use pocketmine\utils\Path;

class Loader extends PluginBase {

    private Config $config;

    private static self $instance;

    /** @var string[] */
    private array $loaded = [];

    public function onLoad(): void {
        self::$instance = $this;

        $packFolder = $this->getDataFolder() . "resources/";
        @mkdir($packFolder);
        foreach (array_slice(scandir($packFolder), 2) as $pack) {
            if (!str_contains($pack, "mcpack")) {
                continue;
            }

            $packPath = Path::join($packFolder, $pack);
            $newPack = new ZippedResourcePack($packPath);
            $rpManager = $this->getServer()->getResourcePackManager();

            $resourcePacks = new \ReflectionProperty($rpManager, "resourcePacks");
            $resourcePacks->setAccessible(true);
            $resourcePacks->setValue($rpManager, array_merge($resourcePacks->getValue($rpManager), [$newPack]));

            $uuidList = new \ReflectionProperty($rpManager, "uuidList");
            $uuidList->setAccessible(true);
            $uuidList->setValue($rpManager, $uuidList->getValue($rpManager) + [strtolower($newPack->getPackId()) => $newPack]);

            $serverForceResources = new \ReflectionProperty($rpManager, "serverForceResources");
            $serverForceResources->setAccessible(true);
            $serverForceResources->setValue($rpManager, true);

            $this->getLogger()->info("§eResourcePack " . $pack . " successfully loaded!");
            $this->loaded[] = $pack;
        }
    }

    public function onEnable(): void {
        $this->getLogger()->info(C::GREEN . "Plugin Enabled - VsrStudio");

        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");

        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $this->getServer()->getPluginManager()->registerEvents(new MoveEvent(), $this);
    }

    /**
     * Akses Config Plugin
     */
    public function getPluginConfig(): Config {
        return $this->config;
    }

    /**
     * Akses Instance Plugin
     */
    public static function getInstance(): self {
        return self::$instance;
    }
}
