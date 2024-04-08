<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_bbebada67d5cff92\muqsit\invmenu\session\network\handler;

use Closure;
use NgLam2911\InvCraft\libs\_bbebada67d5cff92\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}