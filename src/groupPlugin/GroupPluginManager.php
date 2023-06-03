<?php

namespace supercrafter333\AdminLogin\groupPlugin;

use IvanCraft623\RankSystem\RankSystem;
use pocketmine\Server;
use r3pt1s\GroupSystem\GroupSystem;
use supercrafter333\AdminLogin\groupPlugin\standard\GroupSystemPlugin;
use supercrafter333\AdminLogin\groupPlugin\standard\RankSystemPlugin;
use function class_exists;

class GroupPluginManager
{

    protected static GroupPlugin $groupPlugin;

    public static function setGroupPlugin(GroupPlugin $groupPlugin): void
    {
        self::$groupPlugin = $groupPlugin;
    }

    public static function getGroupPlugin(): GroupPlugin
    {
        return self::$groupPlugin;
    }

    public static function startup(): void
    {
        $pluginMgr = Server::getInstance()->getPluginManager();

        if ($pluginMgr->getPlugin("GroupSystem") !== null || class_exists(GroupSystem::class)) {
            self::setGroupPlugin(new GroupSystemPlugin);
            return;
        }

        if ($pluginMgr->getPlugin("RankSystem") !== null || class_exists(RankSystem::class)) {
            self::setGroupPlugin(new RankSystemPlugin);
            return;
        }
    }
}