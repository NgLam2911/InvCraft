<?php

declare(strict_types=1);

namespace NgLamVN\InvCraft;

use pocketmine\item\Item;
use pocketmine\Server;

class Recipe{
	const IIIxIII_MODE = 0;
	const VIxVI_MODE = 1;
	/** @var string $recipe_name */
	public string $recipe_name;
	/** @var Item[] $recipe_data */
	public array $recipe_data;
	/** @var Item $result */
	public Item $result;
	/** @var int $mode */
	public int $mode;

	public function __construct(string $recipe_name, array $recipe_data, Item $result, int $mode){
		$this->recipe_name = $recipe_name;
		$this->recipe_data = $recipe_data;
		$this->result = $result;
		$this->mode = $mode;
	}

	/**
	 * @param string $recipe_name
	 * @param Item[] $recipe_data
	 * @param Item   $result
	 * @param int    $mode
	 *
	 * @return Recipe
	 */
	public static function makeRecipe(string $recipe_name, array $recipe_data, Item $result, int $mode) : Recipe{
		return new Recipe($recipe_name, $recipe_data, $result, $mode);
	}

	/**
	 * @param Recipe|Item[] $other
	 *
	 * @return bool
	 */
	public function isSame(Recipe|array $other) : bool{
		if($other instanceof Recipe){
			$other = $other->getRecipeData();
		}
		foreach(array_keys($this->getRecipeData()) as $key){
			if(!$this->getRecipeData()[$key]->canStackWith($other[$key])){
				return false;
			}
		}
		return true;
	}

	public function isEnough(Recipe|array $other) : bool{
		if($other instanceof Recipe){
			$other = $other->getRecipeData();
		}
		foreach(array_keys($this->getRecipeData()) as $key){
			if(!$this->getRecipeData()[$key]->canStackWith($other[$key])){
				return false;
			}
			if($this->getRecipeData()[$key]->getCount() > $other[$key]->getCount()){
				return false;
			}
		}
		return true;
	}

	/**
	 * @return Item[]
	 */
	public function getRecipeData() : array{
		return $this->recipe_data;
	}

	/**
	 * @param Item[] $data
	 */
	public function setRecipeData(array $data){
		$this->recipe_data = $data;
		$this->getLoader()->setRecipe($this);
	}

	/**
	 * @return string
	 */
	public function getRecipeName() : string{
		return $this->recipe_name;
	}

	/**
	 * @param string $name
	 */
	public function setRecipeName(string $name){
		$old = clone $this;
		$this->getLoader()->removeRecipe($old);
		$this->recipe_name = $name;
		$this->getLoader()->setRecipe($this);
	}

	/**
	 * @return Loader|null
	 */
	public function getLoader() : ?Loader{
		$loader = Server::getInstance()->getPluginManager()->getPlugin("InvCraft");
		if($loader instanceof Loader){
			return $loader;
		}
		return null;
	}

	/**
	 * @param int $index
	 *
	 * @return Item
	 */
	public function getRecipeItem(int $index) : Item{
		return $this->recipe_data[$index];
	}

	/**
	 * @param int  $index
	 * @param Item $item
	 */
	public function setRecipeItem(int $index, Item $item){
		$this->recipe_data[$index] = $item;
		$this->getLoader()->setRecipe($this);
	}

	/**
	 * @return Item
	 */
	public function getResultItem() : Item{
		return $this->result;
	}

	/**
	 * @param Item $item
	 */
	public function setResultItem(Item $item){
		$this->result = $item;
		$this->getLoader()->setRecipe($this);
	}

	public function getMode() : int{
		return $this->mode;
	}

	public function setMode(int $mode){
		$this->mode = $mode;
		$this->getLoader()->setRecipe($this);
	}
}
