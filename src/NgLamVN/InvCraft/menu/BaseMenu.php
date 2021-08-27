<?php
declare(strict_types=1);

namespace NgLamVN\InvCraft\menu;

use muqsit\invmenu\InvMenu;
use NgLamVN\InvCraft\Loader;
use pocketmine\player\Player;

abstract class BaseMenu
{
    /** @var InvMenu */
    public InvMenu $menu;
    /** @var Loader */
    public Loader $loader;
    /** @var Player */
    public Player $player;
    /** @var int */
    public int $mode;

    const IIIxIII_MODE = 0;
    const VIxVI_MODE = 1;

    public function __construct(Player $player, Loader $loader, int $mode = 0)
    {
        $this->player = $player;
        $this->loader = $loader;
        $this->mode = $mode;
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

    public function getMode(): int
	{
        return $this->mode;
    }

    /**
     * @param Player $player
     */
    public function menu(Player $player)
    {
    }
}