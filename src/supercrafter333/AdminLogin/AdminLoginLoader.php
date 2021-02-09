<?php

namespace supercrafter333\AdminLogin;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class AdminLoginLoader extends PluginBase
{

    public $config;
    public $msgconfig;

    public static $instance;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->saveResource("config.yml");
        $this->saveResource("messages.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->msgconfig = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
        self::$instance = $this;
        if ($this->msgconfig->get("version") !== "1.0.0") {
            $this->getLogger()->error("AdminLogin >> OUTDATED CONFIGURATION FILE!! You configuration file messages.yml is outdated, please delete the file and restart your server for using the newest version of the file!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    /*API Part*/
    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function getConfigFile(): Config
    {
        return $this->config;
    }

    public function getMessageConfigFile(): Config
    {
        return $this->msgconfig;
    }

    public function isPurePermsLoadet(): bool
    {
        $pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
        if ($this->getServer()->getPluginManager()->isPluginEnabled($pp)) {
            return true;
        } else {
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return false;
        }
    }

    public function getPurePerms()
    {
        $pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
        return $pp;
    }

    public function getPurePermsUserMgr()
    {
        $pureperms = $this->getPurePerms();
        return $pureperms->getUserDataMgr();
    }

    public function getPurePermsUserGroupName(Player $player)
    {
        $ppUserMgr = $this->getPurePermsUserMgr();
        return $ppUserMgr->getGroup($player)->getName();
    }
    /*End of API Part*/
}