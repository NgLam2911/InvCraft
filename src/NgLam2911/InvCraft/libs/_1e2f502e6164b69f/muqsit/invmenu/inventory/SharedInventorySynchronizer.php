<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_1e2f502e6164b69f\muqsit\invmenu\inventory;

use pocketmine\inventory\Inventory;
use pocketmine\inventory\InventoryListener;
use pocketmine\item\Item;

final class SharedInventorySynchronizer implements InventoryListener{

	public function __construct(
		readonly private Inventory $inventory
	){}

	public function getSynchronizingInventory() : Inventory{
		return $this->inventory;
	}

	public function onContentChange(Inventory $inventory, array $old_contents) : void{
		$this->inventory->setContents($inventory->getContents());
	}

	public function onSlotChange(Inventory $inventory, int $slot, Item $old_item) : void{
		$this->inventory->setItem($slot, $inventory->getItem($slot));
	}
}