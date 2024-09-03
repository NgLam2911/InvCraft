<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_af702d4ff2a6d56d\muqsit\invmenu\type\graphic\network;

use NgLam2911\InvCraft\libs\_af702d4ff2a6d56d\muqsit\invmenu\session\InvMenuInfo;
use NgLam2911\InvCraft\libs\_af702d4ff2a6d56d\muqsit\invmenu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

interface InvMenuGraphicNetworkTranslator{

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void;
}