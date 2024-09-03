<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_af702d4ff2a6d56d\muqsit\invmenu\type\graphic\network;

use NgLam2911\InvCraft\libs\_af702d4ff2a6d56d\muqsit\invmenu\session\InvMenuInfo;
use NgLam2911\InvCraft\libs\_af702d4ff2a6d56d\muqsit\invmenu\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

final class ActorInvMenuGraphicNetworkTranslator implements InvMenuGraphicNetworkTranslator{

	public function __construct(
		readonly private int $actor_runtime_id
	){}

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void{
		$packet->actorUniqueId = $this->actor_runtime_id;
		$packet->blockPosition = new BlockPosition(0, 0, 0);
	}
}