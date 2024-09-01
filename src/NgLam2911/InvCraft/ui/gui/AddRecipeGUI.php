<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\gui;

use NgLam2911\InvCraft\libs\_7450c532b087bcf9\muqsit\invmenu\transaction\InvMenuTransaction;
use NgLam2911\InvCraft\libs\_7450c532b087bcf9\muqsit\invmenu\transaction\InvMenuTransactionResult;
use NgLam2911\InvCraft\crafting\Recipe;
use NgLam2911\InvCraft\crafting\result\RecipeResult;
use NgLam2911\InvCraft\InvCraft;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class AddRecipeGUI extends CraftingGridGUI{

    public function __construct(Player $player, protected string $recipe_name){
        parent::__construct($player);
    }

    protected function mustReturnResult() : bool{
        return true;
    }

    protected function prepare() : void{
        parent::prepare();
        $this->getMenu()->setName("Add Recipe");
        $this->getMenu()->getInventory()->setItem(53, VanillaItems::SLIMEBALL()->setCustomName("Save"));
    }


    protected function onTransaction(InvMenuTransaction $transaction) : InvMenuTransactionResult{
        $slot = $transaction->getAction()->getSlot();
        if ($slot === 53){
            $this->portToGrid();
            if ($this->checkClone()){
                $this->player->sendMessage("This recipe already exists");
                return $transaction->discard();
            }
            $this->saveRecipe();
            return $transaction->discard();
        }
        if (!$this->isInCraftingGrid($slot)){
            return $transaction->discard();
        }
        return $transaction->continue();
    }

    protected function saveRecipe() : void{
        $result = $this->getMenu()->getInventory()->getItem(self::RESULT_SLOT);
        $recipe = Recipe::fromCraftingGrid($this->recipe_name, $this->grid, new RecipeResult($result));
        InvCraft::getInstance()->getRecipeManager()->addRecipe($recipe);
    }

    protected function checkClone() : bool{
        return !is_null(InvCraft::getInstance()->getRecipeManager()->matchCraftingGrid($this->grid));
    }
}