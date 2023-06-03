<?php

namespace supercrafter333\AdminLogin\groupPlugin\standard;

#####
# Adds support for:
# https://github.com/r3pt1s/GroupSystem
#####

use IvanCraft623\RankSystem\session\SessionManager;
use pocketmine\player\Player;
use supercrafter333\AdminLogin\groupPlugin\GroupPlugin;

class RankSystemPlugin implements GroupPlugin
{

    /**
     * @param Player $player
     * @return string|null
     */
    public function getGroupNameByPlayer(Player $player): null|string
    {
        return SessionManager::getInstance()->get($player->getName())->getHighestRank()->getName();
    }
}