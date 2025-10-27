<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\gui;

use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use NgLam2911\InvCraft\crafting\Recipe;
use NgLam2911\InvCraft\event\InvCraftItemEvent;
use NgLam2911\InvCraft\lang\LanguagesManager as Lang;
use NgLam2911\InvCraft\lang\TextKeys as Key;
use NgLam2911\InvCraft\InvCraft;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class CraftingGUI extends CraftingGridGUI {


    protected ?Recipe $matched_recipe = null;

    protected function prepare() : void{
        parent::prepare();
        $this->getMenu()->setName(Lang::getText(Key::GUI_CRAFT_TITLE));
    }

    protected function onTransaction(InvMenuTransaction $transaction) : InvMenuTransactionResult{
        $slot = $transaction->getAction()->getSlot();
        $inv = $this->menu->getInventory();
        $nextItem = $transaction->getAction()->getTargetItem();

        if (!$this->isInCraftingGrid($slot)){
            return $transaction->discard();
        }
        if ($slot === self::RESULT_SLOT){
            $result = $inv->getItem(self::RESULT_SLOT);
            if ($result->isNull()){
                return $transaction->discard();
            }
            $event = new InvCraftItemEvent($this->player, $this->matched_recipe, $this->grid);
            $event->call();
            if ($event->isCancelled()){
                return $transaction->discard();
            }
            $this->takeIngredients();
            return $transaction->continue()->then(function(){
                $this->updateResult();
            });
        }
        $this->updateCraftingGrid($slot % 9, (int)($slot / 9), $nextItem);
        return $transaction->continue();
    }

    protected function updateCraftingGrid(int $slotX, int $slotY, Item $item) : void{
        $this->grid->setItem($slotX, $slotY, $item);
        $this->updateResult();
    }

    protected function updateResult() : void{
        $this->matched_recipe = InvCraft::getInstance()->getRecipeManager()->matchCraftingGrid($this->grid);
        if ($this->matched_recipe !== null){
            $this->menu->getInventory()->setItem(self::RESULT_SLOT, $this->matched_recipe->getResult()->getItem());
        } else {
            $this->menu->getInventory()->setItem(self::RESULT_SLOT, VanillaItems::AIR());
        }
    }

    protected function takeIngredients() : void{
        if ($this->matched_recipe === null){
            return;
        }
        $this->grid->takeIngredients($this->matched_recipe);
        $inv = $this->menu->getInventory();
        for($y = 0; $y < 6; $y++){
            for($x = 0; $x < 6; $x++){
                $item = $this->grid->getItem($x, $y);
                if (is_null($item)){
                    $inv->setItem($y * 9 + $x, VanillaItems::AIR());
                }else{
                    $inv->setItem($y * 9 + $x, $item->isNull() ? VanillaItems::AIR() : $item);
                }
            }
        }
    }
}



