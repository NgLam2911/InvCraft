<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_570e4e8b4962adf5\muqsit\invmenu\session\network\handler;

use Closure;
use NgLam2911\InvCraft\libs\_570e4e8b4962adf5\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}