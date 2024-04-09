<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_75a1441745f19d31\muqsit\invmenu\session\network\handler;

use Closure;
use NgLam2911\InvCraft\libs\_75a1441745f19d31\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}