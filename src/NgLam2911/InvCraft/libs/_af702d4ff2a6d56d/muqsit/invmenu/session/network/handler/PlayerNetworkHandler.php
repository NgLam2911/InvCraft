<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_af702d4ff2a6d56d\muqsit\invmenu\session\network\handler;

use Closure;
use NgLam2911\InvCraft\libs\_af702d4ff2a6d56d\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}