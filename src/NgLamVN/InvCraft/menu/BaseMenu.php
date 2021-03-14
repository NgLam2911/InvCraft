<?php

namespace NgLamVN\InvCraft\menu;

use muqsit\invmenu\InvMenu;
use NgLamVN\InvCraft\Loader;
use pocketmine\Player;

abstract class BaseMenu
{
    /** @var InvMenu */
    public $menu;
    /** @var Loader */
    public $loader;
    /** @var Player */
    public $player;

    public function __construct(Player $player, Loader $loader)
    {
        $this->player = $player;
        $this->loader = $loader;
        $this->menu($player);
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return Loader
     */
    public function getLoader(): Loader
    {
        return $this->loader;
    }

    /**
     * @param Player $player
     */
    public function menu(Player $player)
    {
    }
}