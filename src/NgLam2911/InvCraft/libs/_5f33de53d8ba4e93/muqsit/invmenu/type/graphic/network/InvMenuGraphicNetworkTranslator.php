<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_5f33de53d8ba4e93\muqsit\invmenu\type\graphic\network;

use NgLam2911\InvCraft\libs\_5f33de53d8ba4e93\muqsit\invmenu\session\InvMenuInfo;
use NgLam2911\InvCraft\libs\_5f33de53d8ba4e93\muqsit\invmenu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

interface InvMenuGraphicNetworkTranslator{

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void;
}