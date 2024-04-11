<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_a3fa0c42ab0e8c9e\muqsit\invmenu\type\graphic\network;

use NgLam2911\InvCraft\libs\_a3fa0c42ab0e8c9e\muqsit\invmenu\session\InvMenuInfo;
use NgLam2911\InvCraft\libs\_a3fa0c42ab0e8c9e\muqsit\invmenu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

final class WindowTypeInvMenuGraphicNetworkTranslator implements InvMenuGraphicNetworkTranslator{

	public function __construct(
		readonly private int $window_type
	){}

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void{
		$packet->windowType = $this->window_type;
	}
}