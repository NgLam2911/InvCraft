<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_2d7daee07e06bfc7\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}