<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_2f072b751c6b3f5e\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}