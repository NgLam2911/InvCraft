<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_7f24bd44278ec6ee\muqsit\invmenu\type;

use NgLam2911\InvCraft\libs\_7f24bd44278ec6ee\muqsit\invmenu\InvMenu;
use NgLam2911\InvCraft\libs\_7f24bd44278ec6ee\muqsit\invmenu\type\graphic\InvMenuGraphic;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

interface InvMenuType{

	public function createGraphic(InvMenu $menu, Player $player) : ?InvMenuGraphic;

	public function createInventory() : Inventory;
}