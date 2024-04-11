<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_2f072b751c6b3f5e\muqsit\invmenu\type\util;

use NgLam2911\InvCraft\libs\_2f072b751c6b3f5e\muqsit\invmenu\type\util\builder\ActorFixedInvMenuTypeBuilder;
use NgLam2911\InvCraft\libs\_2f072b751c6b3f5e\muqsit\invmenu\type\util\builder\BlockActorFixedInvMenuTypeBuilder;
use NgLam2911\InvCraft\libs\_2f072b751c6b3f5e\muqsit\invmenu\type\util\builder\BlockFixedInvMenuTypeBuilder;
use NgLam2911\InvCraft\libs\_2f072b751c6b3f5e\muqsit\invmenu\type\util\builder\DoublePairableBlockActorFixedInvMenuTypeBuilder;

final class InvMenuTypeBuilders{

	public static function ACTOR_FIXED() : ActorFixedInvMenuTypeBuilder{
		return new ActorFixedInvMenuTypeBuilder();
	}

	public static function BLOCK_ACTOR_FIXED() : BlockActorFixedInvMenuTypeBuilder{
		return new BlockActorFixedInvMenuTypeBuilder();
	}

	public static function BLOCK_FIXED() : BlockFixedInvMenuTypeBuilder{
		return new BlockFixedInvMenuTypeBuilder();
	}

	public static function DOUBLE_PAIRABLE_BLOCK_ACTOR_FIXED() : DoublePairableBlockActorFixedInvMenuTypeBuilder{
		return new DoublePairableBlockActorFixedInvMenuTypeBuilder();
	}
}