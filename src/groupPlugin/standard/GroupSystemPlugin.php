<?php

namespace supercrafter333\AdminLogin\groupPlugin\standard;

#####
# Adds support for:
# https://github.com/r3pt1s/GroupSystem
#####

use pocketmine\player\Player;
use r3pt1s\GroupSystem\GroupSystem;
use supercrafter333\AdminLogin\groupPlugin\GroupPlugin;

class GroupSystemPlugin implements GroupPlugin
{

    /**
     * @param Player $player
     * @return string|null
     */
    public function getGroupNameByPlayer(Player $player): null|string
    {
        return GroupSystem::getInstance()->getPlayerGroupManager()->getPlayer($player->getName())?->getNextHighestGroup()?->getGroup()->getName();
    }
}