<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\gui;

use NgLam2911\InvCraft\libs\_5753972eb36c8972\muqsit\invmenu\type\InvMenuTypeIds;
use NgLam2911\InvCraft\crafting\CraftingGrid;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\inventory\Inventory;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

/** Base gui for menu that have 6x6 grid, doesn't handle how transaction work */
abstract class CraftingGridGUI extends BaseGUI {
    protected CraftingGrid $grid;

    public const RESULT_SLOT = 34;

    protected function getType() : string{
        return InvMenuTypeIds::TYPE_DOUBLE_CHEST;
    }

    /**
     * @return bool
     * @description Return true if the result of crafting should be returned to player when closing the menu
     */
    protected function mustReturnResult() : bool{
        return false;
    }

    /**
     * @return bool
     * @description Return true if the items in crafting grid should be returned to player when closing the menu
     */
    protected function mustReturnItems() : bool{
        return true;
    }

    protected function prepare() : void{
        $this->grid = new CraftingGrid(6);
        $inv = $this->getMenu()->getInventory();
        for ($y = 0; $y < 6; $y++)
            for ($x = 6; $x < 9; $x++){
                $inv->setItem($y * 9 + $x, VanillaBlocks::STAINED_GLASS_PANE()->setColor(DyeColor::GRAY)->asItem());
            }
        $inv->setItem(self::RESULT_SLOT, VanillaItems::AIR());
    }

    protected function onClose(Player $player, Inventory $inventory) : void{
        if ($this->mustReturnItems()){
            for ($y = 0; $y < 6; $y++){
                for ($x = 0; $x < 6; $x++){
                    $item = $inventory->getItem($y * 9 + $x);
                    if ($item->isNull()){
                        continue;
                    }
                    if ($this->player->getInventory()->canAddItem($item)){
                        $this->player->getInventory()->addItem($item);
                    }else{
                        $this->player->dropItem($item);
                        //TODO: Add it into stash if any plugin gonna support it.
                    }
                }
            }
        }
        if ($this->mustReturnResult()){
            $result = $inventory->getItem(self::RESULT_SLOT);
            if ($player->getInventory()->canAddItem($result)){
                $player->getInventory()->addItem($result);
            }else{
                $player->dropItem($result);
            }
        }
    }

    protected function isInCraftingGrid(int $slot) : bool{
        if ($slot === self::RESULT_SLOT){
            return true;
        }
        $collumn = $slot % 9;
        return $collumn >= 0 && $collumn <= 5;
    }

    protected function getCraftingGrid() : CraftingGrid{
        return $this->grid;
    }

    protected function portToGrid() : void{
        $inv = $this->getMenu()->getInventory();
        for ($y = 0; $y < 6; $y++){
            for ($x = 0; $x < 6; $x++){
                $this->grid->setItem($x, $y, $inv->getItem($y * 9 + $x), false);
            }
        }
        $this->grid->seekRecipeBounds();
    }
}