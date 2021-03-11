<?php

namespace NgLamVN\InvCraft\menu;

use pocketmine\Player;

abstract class BaseMenu
{
    public $menu;

    public function __construct(Player $player)
    {
        $this->menu($player);
    }

    public function menu(Player $player)
    {

    }

    public function MenuListener()
    {

    }
}