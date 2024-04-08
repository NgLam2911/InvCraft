<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_8944152000937ebe\muqsit\invmenu\type\graphic\network;

use NgLam2911\InvCraft\libs\_8944152000937ebe\muqsit\invmenu\session\InvMenuInfo;
use NgLam2911\InvCraft\libs\_8944152000937ebe\muqsit\invmenu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

interface InvMenuGraphicNetworkTranslator{

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void;
}