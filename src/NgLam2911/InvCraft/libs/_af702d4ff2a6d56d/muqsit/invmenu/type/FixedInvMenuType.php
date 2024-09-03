<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_af702d4ff2a6d56d\muqsit\invmenu\type;

/**
 * An InvMenuType with a fixed inventory size.
 */
interface FixedInvMenuType extends InvMenuType{

	/**
	 * Returns size (number of slots) of the inventory.
	 *
	 * @return int
	 */
	public function getSize() : int;
}