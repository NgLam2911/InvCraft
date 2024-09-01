<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_7450c532b087bcf9\muqsit\invmenu\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInvMenuGraphic extends InvMenuGraphic{

	public function getPosition() : Vector3;
}