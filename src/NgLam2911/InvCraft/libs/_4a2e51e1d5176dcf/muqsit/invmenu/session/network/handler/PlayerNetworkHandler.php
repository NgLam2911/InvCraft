<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_4a2e51e1d5176dcf\muqsit\invmenu\session\network\handler;

use Closure;
use NgLam2911\InvCraft\libs\_4a2e51e1d5176dcf\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}