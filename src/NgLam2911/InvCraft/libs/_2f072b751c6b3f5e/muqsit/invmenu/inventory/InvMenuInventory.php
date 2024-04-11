<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_2f072b751c6b3f5e\muqsit\invmenu\inventory;

use pocketmine\block\inventory\BlockInventory;
use pocketmine\inventory\SimpleInventory;
use pocketmine\world\Position;

final class InvMenuInventory extends SimpleInventory implements BlockInventory{

	private Position $holder;

	public function __construct(int $size){
		parent::__construct($size);
		$this->holder = new Position(0, 0, 0, null);
	}

	public function getHolder() : Position{
		return $this->holder;
	}
}