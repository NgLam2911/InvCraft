<?php
declare(strict_types=1);

namespace NgLamVN\InvCraft\menu;

use Closure;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\type\InvMenuTypeIds;
use NgLamVN\InvCraft\Loader;
use NgLamVN\InvCraft\Recipe;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;

class EditRecipeMenu extends BaseMenu
{
    const VIxVI_PROTECTED_SLOT = [6, 7, 8, 15, 16, 17, 24, 25, 26, 33, 35, 42, 43, 44, 51, 52];
    const VIxVI_RESULT_SLOT = 34;
    const IIIxIII_PROTECTED_SLOT = [0,1,2,3,4,5,6,7,8,9,10,14,15,16,17,18,19,23,24,26,27,28,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52];
    const IIIxIII_RESULT_SLOT = 25;
    const SAVE_SLOT = 53;
    /** @var Recipe $recipe */
    public Recipe $recipe;

    public function __construct(Player $player, Loader $loader, Recipe $recipe)
    {
        $this->recipe = $recipe;
        $mode = $recipe->getMode();
        parent::__construct($player, $loader, $mode);
    }

    public function menu(Player $player): void
    {
        $this->menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $this->menu->setName($this->getLoader()->getProvider()->getMessage("menu.edit"));
        $this->menu->setListener(Closure::fromCallable([$this, "MenuListener"]));
        $inv = $this->menu->getInventory();

        $ids = explode(":", $this->getLoader()->getProvider()->getMessage("menu.item"));
        $item = ItemFactory::getInstance()->get((int)$ids[0], (int)$ids[1]);
        for ($i = 0; $i <= 52; $i++)
        {
            if (in_array($i, $this->getProtectedSlot()))
            {
                $inv->setItem($i, $item);
            }
        }
        $idsave = explode(":", $this->getLoader()->getProvider()->getMessage("menu.save.item"));
        $save = ItemFactory::getInstance()->get((int)$idsave[0], (int)$idsave[1])->setCustomName($this->getLoader()->getProvider()->getMessage("menu.save.name"));
        $inv->setItem(self::SAVE_SLOT, $save);
        $this->pasteRecipe($this->recipe);

        $this->menu->send($player);
    }

    public function pasteRecipe(Recipe $recipe): void
    {
        $recipe_data = $recipe->getRecipeData();
        $result = $recipe->getResultItem();
        $inv = $this->menu->getInventory();
        $inv->setItem($this->getResultSlot(), $result);

        $j = 0;
        for ($i = 0; $i <= 52; $i++)
        {
            if (!in_array($i, $this->getProtectedSlot()))
                if ($i !== $this->getResultSlot())
                {
                    $inv->setItem($i, $recipe_data[$j]);
                    $j++;
                }
        }
    }

    public function MenuListener(InvMenuTransaction $transaction) : InvMenuTransactionResult
	{
        if (in_array($transaction->getAction()->getSlot(), $this->getProtectedSlot()))
        {
            return $transaction->discard();
        }
        if ($transaction->getAction()->getSlot() === self::SAVE_SLOT)
        {
            $this->save();
            $transaction->getPlayer()->removeCurrentWindow();
            return $transaction->discard();
        }
        return $transaction->continue();
    }

    public function save(): void
    {
        $recipe_data = $this->makeRecipeData();
        $result = $this->menu->getInventory()->getItem($this->getResultSlot());
        $this->recipe->setRecipeData($recipe_data);
        $this->recipe->setResultItem($result);
    }

    public function makeRecipeData(): array
    {
        $recipe_data = [];
        for ($i = 0; $i <= 53; $i++)
        {
            if (!in_array($i, $this->getProtectedSlot()))
                if (($i !== $this->getResultSlot()) and ($i !== self::SAVE_SLOT))
                {
                    $item = $this->menu->getInventory()->getItem($i);
                    array_push($recipe_data, $item);
                }
        }
        return $recipe_data;
    }

    public function getResultSlot(): int
    {
        if ($this->getMode() == self::IIIxIII_MODE)
        {
            return self::IIIxIII_RESULT_SLOT;
        }
        return self::VIxVI_RESULT_SLOT;
    }

    public function getProtectedSlot(): array
    {
        if ($this->getMode() == self::IIIxIII_MODE)
        {
            return self::IIIxIII_PROTECTED_SLOT;
        }
        return self::VIxVI_PROTECTED_SLOT;
    }
}
