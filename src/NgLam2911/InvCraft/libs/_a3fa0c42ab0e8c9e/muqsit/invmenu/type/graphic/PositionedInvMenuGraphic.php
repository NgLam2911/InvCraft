<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_a3fa0c42ab0e8c9e\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}