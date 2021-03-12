<?php

namespace NgLamVN\InvCraft\menu;

use muqsit\invmenu\InvMenu;
use NgLamVN\InvCraft\Loader;
use pocketmine\Player;

abstract class BaseMenu
{
    /** @var InvMenu */
    public $menu;

    public $loader;

    public function __construct(Player $player, Loader $loader)
    {
        $this->loader = $loader;
        $this->menu($player);
    }

    public function getLoader(): Loader
    {
        return $this->loader;
    }

    public function menu(Player $player)
    {
    }
}