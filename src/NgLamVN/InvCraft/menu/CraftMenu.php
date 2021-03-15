<?php

namespace NgLamVN\InvCraft\menu;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\Player;

class CraftMenu extends BaseMenu
{
    const PROTECTED_SLOT = [6, 7, 8, 15, 16, 17, 24, 25, 26, 33, 35, 42, 43, 44, 51, 52, 53];
    //34 is result item.

    public function menu(Player $player)
    {
        $this->menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $this->menu->setName("BigCraftingTable");
        $this->menu->setListener(\Closure::fromCallable([$this, "MenuListener"]));
        $this->menu->setInventoryCloseListener(\Closure::fromCallable([$this, "MenuCloseListener"]));
        $inv = $this->menu->getInventory();
        $item = Item::get(Item::STAINED_GLASS_PANE, 2);
        for ($i = 0; $i <= 53; $i++)
        {
            if (in_array($i, self::PROTECTED_SLOT))
            {
                $inv->setItem($i, $item);
            }
        }

        $this->menu->send($player);
    }

    public function MenuListener(InvMenuTransaction $transaction)
    {
        if (in_array($transaction->getAction()->getSlot(), self::PROTECTED_SLOT))
        {
            return $transaction->discard();
        }
        if ($transaction->getAction()->getSlot() === 34)
        {
            $result = $this->menu->getInventory()->getItem(34);
            if ($result->getId() == Item::AIR)
            {
                return $transaction->discard();
            }
            $this->clearCraftItem();
            return $transaction->continue();
        }
        $slot = $transaction->getAction()->getSlot();
        $nextitem = $transaction->getAction()->getTargetItem();
        $recipe_data = $this->makeRecipeData($slot, $nextitem);
        foreach ($this->getLoader()->getRecipes() as $recipe)
        {
            if ($recipe->getRecipeData() == $recipe_data)
            {
                $this->setResult($recipe->getResultItem());
                return $transaction->continue();
            }
        }
        $this->setResult(Item::get(0));
        return $transaction->continue();
    }

    public function MenuCloseListener(Player $player, Inventory $inventory)
    {
        for ($i = 0; $i <= 53; $i++)
        {
            if (!in_array($i, self::PROTECTED_SLOT))
                if ($i !== 34)
                {
                    $item = $inventory->getItem($i);
                    if ($item->getId() !== Item::AIR)
                        $player->getInventory()->addItem($item);
                }
        }
    }

    public function makeRecipeData(int $slot, Item $nextitem): array
    {
        $recipe_data = [];
        for ($i = 0; $i <= 53; $i++)
        {
            if (!in_array($i, self::PROTECTED_SLOT))
                if ($i !== 34)
                {
                    if ($i == $slot)
                    {
                        array_push($recipe_data, $nextitem);
                    }
                    else
                    {
                        $item = $this->menu->getInventory()->getItem($i);
                        array_push($recipe_data, $item);
                    }
                }
        }
        return $recipe_data;
    }

    public function setResult(Item $item)
    {
        $this->menu->getInventory()->setItem(34, $item);
    }

    public function clearCraftItem()
    {
        for ($i = 0; $i <= 53; $i++)
        {
            if ((!in_array($i, self::PROTECTED_SLOT)) and ($i !== 34))
            {
                $this->menu->getInventory()->setItem($i, Item::get(Item::AIR));
            }
        }
    }
}
