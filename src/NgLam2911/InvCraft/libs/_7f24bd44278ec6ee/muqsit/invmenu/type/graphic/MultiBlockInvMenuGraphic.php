<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_7f24bd44278ec6ee\muqsit\invmenu\type\graphic;

use LogicException;
use NgLam2911\InvCraft\libs\_7f24bd44278ec6ee\muqsit\invmenu\type\graphic\network\InvMenuGraphicNetworkTranslator;
use pocketmine\inventory\Inventory;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

final class MultiBlockInvMenuGraphic implements PositionedInvMenuGraphic{

	/**
	 * @param PositionedInvMenuGraphic[] $graphics
	 */
	public function __construct(
		readonly private array $graphics
	){}

	private function first() : PositionedInvMenuGraphic{
		$first = current($this->graphics);
		if($first === false){
			throw new LogicException("Tried sending inventory from a multi graphic consisting of zero entries");
		}

		return $first;
	}

	public function send(Player $player, ?string $name) : void{
		foreach($this->graphics as $graphic){
			$graphic->send($player, $name);
		}
	}

	public function sendInventory(Player $player, Inventory $inventory) : bool{
		return $this->first()->sendInventory($player, $inventory);
	}

	public function remove(Player $player) : void{
		foreach($this->graphics as $graphic){
			$graphic->remove($player);
		}
	}

	public function getNetworkTranslator() : ?InvMenuGraphicNetworkTranslator{
		return $this->first()->getNetworkTranslator();
	}

	public function getPosition() : Vector3{
		return $this->first()->getPosition();
	}

	public function getAnimationDuration() : int{
		$max = 0;
		foreach($this->graphics as $graphic){
			$duration = $graphic->getAnimationDuration();
			if($duration > $max){
				$max = $duration;
			}
		}
		return $max;
	}
}