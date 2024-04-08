<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_8944152000937ebe\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}