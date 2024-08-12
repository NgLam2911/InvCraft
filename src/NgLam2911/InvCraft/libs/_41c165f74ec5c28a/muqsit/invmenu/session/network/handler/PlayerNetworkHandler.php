<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_41c165f74ec5c28a\muqsit\invmenu\session\network\handler;

use Closure;
use NgLam2911\InvCraft\libs\_41c165f74ec5c28a\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}