<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\gui;

use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use NgLam2911\InvCraft\crafting\Recipe;
use NgLam2911\InvCraft\crafting\result\RecipeResult;
use NgLam2911\InvCraft\InvCraft;
use pocketmine\item\VanillaItems;

class EditRecipeGUI extends ViewRecipeGUI {

    protected function prepare() : void{
        parent::prepare();
        $this->getMenu()->setName("Edit Recipe");
        $this->getMenu()->getInventory()->setItem(53, VanillaItems::SLIMEBALL()->setCustomName("Save"));
    }

    protected function onTransaction(InvMenuTransaction $transaction) : InvMenuTransactionResult{
        $slot = $transaction->getAction()->getSlot();
        if ($slot === 53){
            $this->portToGrid();
            if ($this->checkClone()){
                $this->player->sendMessage("This recipe already exists or you didn't change anything");
                return $transaction->discard();
            }
            $this->saveRecipe();
            return $transaction->discard();
        }
        if ($this->isInCraftingGrid($slot)){
            return $transaction->discard();
        }
        return $transaction->continue();
    }

    protected function saveRecipe() : void{
        $result = $this->getMenu()->getInventory()->getItem(self::RESULT_SLOT);
        $recipe = Recipe::fromCraftingGrid($this->recipe->getName(), $this->grid, new RecipeResult($result, $this->recipe->getResult()->getTransferInfos()));
        InvCraft::getInstance()->getRecipeManager()->updateRecipe($recipe);
    }

    protected function checkClone() : bool{
        return !is_null(InvCraft::getInstance()->getRecipeManager()->matchCraftingGrid($this->grid));
    }
}