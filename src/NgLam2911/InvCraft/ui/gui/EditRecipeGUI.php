<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\gui;

use NgLam2911\InvCraft\libs\_6c9046632b65a4c3\muqsit\invmenu\transaction\InvMenuTransaction;
use NgLam2911\InvCraft\libs\_6c9046632b65a4c3\muqsit\invmenu\transaction\InvMenuTransactionResult;
use NgLam2911\InvCraft\crafting\Recipe;
use NgLam2911\InvCraft\crafting\result\RecipeResult;
use NgLam2911\InvCraft\InvCraft;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

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
            return $transaction->discard()->then(function(Player $player){
                $this->menu->onClose($player);
            });
        }
        if (!$this->isInCraftingGrid($slot)){
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
        $matched = InvCraft::getInstance()->getRecipeManager()->matchCraftingGrid($this->grid);
        if ($matched === null){
            return false;
        }
        if ($matched->getName() === $this->recipe->getName()){
            if ($matched->getResult()->getItem()->equals($this->recipe->getResult()->getItem())){
                return true;
            }
            return false;
        }
        return true;
    }
}