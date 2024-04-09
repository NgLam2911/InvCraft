<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_75a1441745f19d31\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}