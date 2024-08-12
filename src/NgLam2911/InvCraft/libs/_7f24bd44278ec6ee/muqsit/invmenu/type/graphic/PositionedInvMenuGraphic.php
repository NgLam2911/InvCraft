<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_7f24bd44278ec6ee\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}