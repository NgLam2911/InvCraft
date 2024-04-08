<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_4a2e51e1d5176dcf\muqsit\invmenu\type;

use NgLam2911\InvCraft\libs\_4a2e51e1d5176dcf\muqsit\invmenu\InvMenu;
use NgLam2911\InvCraft\libs\_4a2e51e1d5176dcf\muqsit\invmenu\type\graphic\InvMenuGraphic;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

interface InvMenuType{

	public function createGraphic(InvMenu $menu, Player $player) : ?InvMenuGraphic;

	public function createInventory() : Inventory;
}