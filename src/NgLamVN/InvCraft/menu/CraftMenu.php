<?php

namespace NgLamVN\InvCraft\menu;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use pocketmine\item\Item;
use pocketmine\Player;

class CraftMenu extends BaseMenu
{
    const PROTECTED_SLOT = [6, 7, 8, 15, 16, 17, 24, 25, 26, 33, 35, 42, 43, 44, 51, 52, 53];
    //34 is result item.

    /** @var InvMenu $menu */
    public $menu;

    public function __construct(Player $player)
    {
        parent::__construct($player);
    }

    public function menu(Player $player)
    {
        parent::menu($player);

        $this->menu = new InvMenu(InvMenu::TYPE_DOUBLE_CHEST);
        $this->menu->setName("BigCraftingTable");
        $this->menu->setListener(\Closure::fromCallable([$this, "MenuListener"]));
        $inv = $this->menu->getInventory();
        $item = Item::get(Item::STAINED_GLASS_PANE, 2);
        for ($i = 0; $i <= 53; $i++)
        {
            if (in_array($i, self::PROTECTED_SLOT))
            {
                $inv->setItem($i, $item);
            }
        }

    }

    public function MenuListener(InvMenuTransaction $transaction)
    {
        if (in_array($transaction->getAction()->getSlot(), self::PROTECTED_SLOT))
        {
            $transaction->discard();
            return;
        }

    }

    public function makeRecipeData(): array
    {
        $recipe_data = [];
        for ($i = 0; $i <= 53; $i++)
        {
            if (!in_array($i, self::PROTECTED_SLOT))
                if ($i !== 34)
                {
                    $item = $this->menu->getInventory()->getItem($i);
                    array_push($recipe_data, $item);
                }
        }
        return $recipe_data;
    }

}
