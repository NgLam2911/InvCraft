<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_570e4e8b4962adf5\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}