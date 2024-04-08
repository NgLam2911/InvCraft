<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_4a2e51e1d5176dcf\muqsit\invmenu\type\util\builder;

use NgLam2911\InvCraft\libs\_4a2e51e1d5176dcf\muqsit\invmenu\type\BlockFixedInvMenuType;
use NgLam2911\InvCraft\libs\_4a2e51e1d5176dcf\muqsit\invmenu\type\graphic\network\BlockInvMenuGraphicNetworkTranslator;

final class BlockFixedInvMenuTypeBuilder implements InvMenuTypeBuilder{
	use BlockInvMenuTypeBuilderTrait;
	use FixedInvMenuTypeBuilderTrait;
	use GraphicNetworkTranslatableInvMenuTypeBuilderTrait;

	public function __construct(){
		$this->addGraphicNetworkTranslator(BlockInvMenuGraphicNetworkTranslator::instance());
	}

	public function build() : BlockFixedInvMenuType{
		return new BlockFixedInvMenuType($this->getBlock(), $this->getSize(), $this->getGraphicNetworkTranslator());
	}
}