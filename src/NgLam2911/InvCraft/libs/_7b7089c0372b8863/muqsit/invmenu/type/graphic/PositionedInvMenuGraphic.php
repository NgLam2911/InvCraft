<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_7b7089c0372b8863\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}