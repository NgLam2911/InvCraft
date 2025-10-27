<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}