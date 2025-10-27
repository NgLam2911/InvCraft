<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\gui;

use NgLam2911\InvCraft\libs\_dbeaa3993dab2002\muqsit\invmenu\transaction\InvMenuTransaction;
use NgLam2911\InvCraft\libs\_dbeaa3993dab2002\muqsit\invmenu\transaction\InvMenuTransactionResult;
use NgLam2911\InvCraft\crafting\Recipe;
use NgLam2911\InvCraft\lang\LanguagesManager as Lang;
use NgLam2911\InvCraft\lang\TextKeys as Key;
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
        $this->getMenu()->setName(Lang::getText(Key::GUI_VIEW_TITLE));
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