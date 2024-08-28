<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\gui;

use NgLam2911\InvCraft\libs\_5753972eb36c8972\muqsit\invmenu\transaction\InvMenuTransaction;
use NgLam2911\InvCraft\libs\_5753972eb36c8972\muqsit\invmenu\transaction\InvMenuTransactionResult;
use NgLam2911\InvCraft\crafting\Recipe;
use pocketmine\player\Player;

class ViewRecipeGUI extends CraftingGridGUI {

    public function __construct(Player $player, protected Recipe $recipe){
        parent::__construct($player);
    }

    protected function mustReturnItems() : bool{
        return false;
    }

    protected function prepare() : void{
        parent::prepare();
        $this->getMenu()->setName("View Recipe");
        $this->getMenu()->getInventory()->setItem(self::RESULT_SLOT, $this->recipe->getResult()->getItem());
        for($y = 0; $y < $this->recipe->getHeight(); $y++){
            for($x = 0; $x < $this->recipe->getWidth(); $x++){
                $ingredient = $this->recipe->getIngredient($x, $y);
                if (is_null($ingredient)){
                    continue;
                }
                $this->getMenu()->getInventory()->setItem($y * 9 + $x, $ingredient->getItem());
            }
        }
    }

    protected function onTransaction(InvMenuTransaction $transaction) : InvMenuTransactionResult{
        return $transaction->discard();
    }
}