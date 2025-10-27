<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\muqsit\invmenu\session\network\handler;

use Closure;
use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\muqsit\invmenu\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}