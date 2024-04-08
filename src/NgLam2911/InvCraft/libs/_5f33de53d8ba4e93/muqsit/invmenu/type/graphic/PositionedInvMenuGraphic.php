<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_5f33de53d8ba4e93\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}