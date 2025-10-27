<?php
declare(strict_types = 1);

namespace NgLam2911\InvCraft\ui\gui;

use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\muqsit\invmenu\InvMenu;
use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\muqsit\invmenu\transaction\InvMenuTransaction;
use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\muqsit\invmenu\transaction\InvMenuTransactionResult;
use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

abstract class BaseGUI {

    protected InvMenu $menu;

    public function __construct(protected Player $player){
        $this->menu = InvMenu::create($this->getType());
        $this->prepare();
        $this->menu->setListener($this->onTransaction(...));
        $this->menu->setInventoryCloseListener($this->onClose(...));
    }

    protected function getMenu() : InvMenu{
        return $this->menu;
    }

    public function sendToPlayer() : void{
        $this->menu->send($this->player);
    }

    protected function getType() : string{
        return InvMenuTypeIds::TYPE_CHEST;
    }

    // Define menu content
    protected function prepare() : void{}

    // Handle menu transaction
    protected abstract function onTransaction(InvMenuTransaction $transaction) : InvMenuTransactionResult;

    // Optional: Do sth when player close menu
    protected function onClose(Player $player, Inventory $inventory) : void{}
}