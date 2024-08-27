<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_1e2f502e6164b69f\muqsit\invmenu\type\graphic\network;

use NgLam2911\InvCraft\libs\_1e2f502e6164b69f\muqsit\invmenu\session\InvMenuInfo;
use NgLam2911\InvCraft\libs\_1e2f502e6164b69f\muqsit\invmenu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

final class WindowTypeInvMenuGraphicNetworkTranslator implements InvMenuGraphicNetworkTranslator{

	public function __construct(
		readonly private int $window_type
	){}

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void{
		$packet->windowType = $this->window_type;
	}
}