<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_a3fa0c42ab0e8c9e\muqsit\invmenu\session\network\handler;

use Closure;
use NgLam2911\InvCraft\libs\_a3fa0c42ab0e8c9e\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}