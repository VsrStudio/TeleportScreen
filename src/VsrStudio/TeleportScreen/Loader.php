<?php

namespace VsrStudio\TeleportScreen;

use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ZippedResourcePack;
use Symfony\Component\Filesystem\Path;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use VsrStudio\TeleportScreen\event\MoveEvent;

class Loader extends PluginBase {

    private Config $config;

    private static self $instance;

    public function onLoad(): void {
        self::$instance = $this;
    }

    public function onEnable(): void {
        $this->getLogger()->info(C::GREEN . "Plugin Enabled - VsrStudio");

        $this->saveDefaultConfig();
        $this->saveResource("config.yml");
        $this->saveResource("TeleportScreen.mcpack");

        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $this->getServer()->getPluginManager()->registerEvents(new MoveEvent(), $this);

        $this->loadResourcePack("TeleportScreen.mcpack");
    }

    private function loadResourcePack(string $file): void {
        $packPath = Path::join($this->getDataFolder(), $file);

        if (!file_exists($packPath)) {
            $this->getLogger()->warning("Resource pack '$file' not found in plugin_data.");
            return;
        }

        $rpManager = $this->getServer()->getResourcePackManager();
        $pack = new ZippedResourcePack($packPath);

        $rpManager->setResourceStack(array_merge($rpManager->getResourceStack(), [$pack]));

        $reflection = new \ReflectionProperty($rpManager, "serverForceResources");
        $reflection->setAccessible(true);
        $reflection->setValue($rpManager, true);

        $this->getLogger()->info("§aResource pack '$file' successfully loaded and forced.");
    }

    public function getPluginConfig(): Config {
        return $this->config;
    }

    public static function getInstance(): self {
        return self::$instance;
    }
}
