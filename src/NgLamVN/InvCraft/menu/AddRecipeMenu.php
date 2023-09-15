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
use pocketmine\item\ItemTypeIds;
use pocketmine\player\Player;

class AddRecipeMenu extends BaseMenu{
	const VIxVI_PROTECTED_SLOT = [6, 7, 8, 15, 16, 17, 24, 25, 26, 33, 35, 42, 43, 44, 51, 52];
	const VIxVI_RESULT_SLOT = 34;
	const IIIxIII_PROTECTED_SLOT = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 14, 15, 16, 17, 18, 19, 23, 24, 26, 27, 28, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52];
	const IIIxIII_RESULT_SLOT = 25;
	const SAVE_SLOT = 53;
	/** @var string */
	public string $recipe_name;

	public function __construct(Player $player, Loader $loader, int $mode, $recipe_name){
		$this->recipe_name = $recipe_name;
		$inv = $this->menu->getInventory();
		parent::__construct($player, $loader, $mode);
	}

	public function menu(Player $player) : void{
		$this->menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
		$this->menu->setName($this->getLoader()->getProvider()->getMessage("menu.add"));
		$this->menu->setListener(Closure::fromCallable([$this, "MenuListener"]));
		

		$ids = $this->getLoader()->getProvider()->getMessage("menu.item");
		$item = $this->getLoader()->getProvider()->stringToItem(strtolower($ids));
		for($i = 0; $i <= 52; $i++){
			if(in_array($i, $this->getProtectedSlot())){
				$inv->setItem($i, $item);
			}
		}
		$idsave = $this->getLoader()->getProvider()->getMessage("menu.save.item");

		$save = $this->getLoader()->getProvider()->stringToItem(strtolower($idsave))->setCustomName($this->getLoader()->getProvider()->getMessage("menu.save.name"));$result->isNull()
		$inv->setItem(self::SAVE_SLOT, $save);

		$this->menu->send($player);
	}

	public function getProtectedSlot() : array{
		if($this->getMode() == self::IIIxIII_MODE){
			return self::IIIxIII_PROTECTED_SLOT;
		}
		return self::VIxVI_PROTECTED_SLOT;
	}

	public function MenuListener(InvMenuTransaction $transaction) : InvMenuTransactionResult{
		if(in_array($transaction->getAction()->getSlot(), $this->getProtectedSlot())){
			return $transaction->discard();
		}
		if($transaction->getAction()->getSlot() === self::SAVE_SLOT){
			$this->save();
			$this->menu->onClose($transaction->getPlayer());
			return $transaction->discard();
		}
		return $transaction->continue();
	}

	public function save() : void{
		$recipe_data = $this->makeRecipeData();
		$result = $this->menu->getInventory()->getItem($this->getResultSlot());

		if($result->isNull()){
			$this->getPlayer()->sendMessage($this->getLoader()->getProvider()->getMessage("msg.missresult"));
			return;
		}
		$recipe = Recipe::makeRecipe($this->recipe_name, $recipe_data, $result, $this->getMode());
		foreach($this->getLoader()->getRecipes() as $other){
			if($other->isSame($recipe)){
				if($other->getMode() == $recipe->getMode()){
					$this->getPlayer()->sendMessage($this->getLoader()->getProvider()->getMessage("msg.sametyperecipe"));
					break;
				}
			}
		}
		$this->getLoader()->setRecipe($recipe);
	}

	public function makeRecipeData() : array{
		$recipe_data = [];
		for($i = 0; $i <= 53; $i++){
			if(!in_array($i, $this->getProtectedSlot()))
				if(($i !== $this->getResultSlot()) and ($i !== self::SAVE_SLOT)){
					$item = $this->menu->getInventory()->getItem($i);
					$recipe_data[] = $item;
				}
		}
		return $recipe_data;
	}

	public function getResultSlot() : int{
		if($this->getMode() == self::IIIxIII_MODE){
			return self::IIIxIII_RESULT_SLOT;
		}
		return self::VIxVI_RESULT_SLOT;
	}
}
