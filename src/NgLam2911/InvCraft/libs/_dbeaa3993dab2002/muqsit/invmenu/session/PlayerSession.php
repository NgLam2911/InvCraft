<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_dbeaa3993dab2002\muqsit\invmenu\session;

use NgLam2911\InvCraft\libs\_dbeaa3993dab2002\muqsit\invmenu\session\network\PlayerNetwork;
use pocketmine\player\Player;

final class PlayerSession{

	public ?PlayerWindowDispatcher $dispatcher = null;
	public ?InvMenuInfo $current = null;

	public function __construct(
		readonly public Player $player,
		readonly public PlayerNetwork $network
	){}

	/**
	 * @internal
	 */
	public function finalize() : void{
		$this->network->finalize();
		$this->dispatcher?->finalize(); // dispatcher finalized first, it has authority to nullify current
		$this->dispatcher = null;
		if($this->current !== null){
			$this->current->graphic->remove($this->player);
			$this->player->removeCurrentWindow();
			$this->current = null;
		}
	}
}