<?php

namespace supercrafter333\AdminLogin\groupPlugin;

use pocketmine\player\Player;

interface GroupPlugin
{

    public function getGroupNameByPlayer(Player $player): null|string;
}