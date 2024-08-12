<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_41c165f74ec5c28a\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}