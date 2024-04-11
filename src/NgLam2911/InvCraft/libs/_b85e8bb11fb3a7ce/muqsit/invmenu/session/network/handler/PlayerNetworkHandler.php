<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_b85e8bb11fb3a7ce\muqsit\invmenu\session\network\handler;

use Closure;
use NgLam2911\InvCraft\libs\_b85e8bb11fb3a7ce\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}