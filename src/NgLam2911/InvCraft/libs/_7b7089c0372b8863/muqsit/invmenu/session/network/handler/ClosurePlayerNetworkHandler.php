<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_7b7089c0372b8863\muqsit\invmenu\session\network\handler;

use Closure;
use NgLam2911\InvCraft\libs\_7b7089c0372b8863\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

final class ClosurePlayerNetworkHandler implements PlayerNetworkHandler{

	/**
	 * @param Closure(Closure) : NetworkStackLatencyEntry $creator
	 */
	public function __construct(
		readonly private Closure $creator
	){}

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry{
		return ($this->creator)($then);
	}
}