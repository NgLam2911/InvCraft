<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_4a2e51e1d5176dcf\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}