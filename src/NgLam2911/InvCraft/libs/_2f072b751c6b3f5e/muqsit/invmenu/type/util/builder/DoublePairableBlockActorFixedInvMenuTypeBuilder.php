<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_2f072b751c6b3f5e\muqsit\invmenu\type\util\builder;

use LogicException;
use NgLam2911\InvCraft\libs\_2f072b751c6b3f5e\muqsit\invmenu\type\DoublePairableBlockActorFixedInvMenuType;
use NgLam2911\InvCraft\libs\_2f072b751c6b3f5e\muqsit\invmenu\type\graphic\network\BlockInvMenuGraphicNetworkTranslator;

final class DoublePairableBlockActorFixedInvMenuTypeBuilder implements InvMenuTypeBuilder{
	use BlockInvMenuTypeBuilderTrait;
	use FixedInvMenuTypeBuilderTrait;
	use GraphicNetworkTranslatableInvMenuTypeBuilderTrait;
	use AnimationDurationInvMenuTypeBuilderTrait;

	private ?string $block_actor_id = null;

	public function __construct(){
		$this->addGraphicNetworkTranslator(BlockInvMenuGraphicNetworkTranslator::instance());
	}

	public function setBlockActorId(string $block_actor_id) : self{
		$this->block_actor_id = $block_actor_id;
		return $this;
	}

	private function getBlockActorId() : string{
		return $this->block_actor_id ?? throw new LogicException("No block actor ID was specified");
	}

	public function build() : DoublePairableBlockActorFixedInvMenuType{
		return new DoublePairableBlockActorFixedInvMenuType($this->getBlock(), $this->getSize(), $this->getBlockActorId(), $this->getGraphicNetworkTranslator(), $this->getAnimationDuration());
	}
}