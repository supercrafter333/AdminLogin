<?php

namespace supercrafter333\AdminLogin;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use supercrafter333\AdminLogin\groupPlugin\GroupPluginManager;

class AdminLoginLoader extends PluginBase
{
    use SingletonTrait;

    private Config $msgconfig;

    public function onEnable(): void
    {
        self::setInstance($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

        $this->saveResource("config.yml");
        $this->saveResource("messages.yml");

        $this->msgconfig = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
        if ($this->msgconfig->get("version") < "1.0.0") {
            $this->getLogger()->error("AdminLogin >> OUTDATED CONFIGURATION FILE!! You configuration file messages.yml is outdated, please delete the file and restart your server for using the newest version of the file!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }

        GroupPluginManager::startup();
    }


    /*API Part*/

    /**
     * @return Config
     */
    public function getConfigFile(): Config
    {
        return $this->getConfig();
    }

    /**
     * @return Config
     */
    public function getMessageConfigFile(): Config
    {
        return $this->msgconfig;
    }
    /*End of API Part*/
}