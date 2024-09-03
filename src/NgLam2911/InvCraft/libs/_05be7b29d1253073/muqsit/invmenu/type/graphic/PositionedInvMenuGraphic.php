<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_05be7b29d1253073\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}