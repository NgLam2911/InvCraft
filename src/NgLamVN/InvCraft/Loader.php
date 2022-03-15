<?php
declare(strict_types=1);

namespace NgLamVN\InvCraft;

use JinodkDevTeam\utils\ItemUtils;
use muqsit\invmenu\InvMenuHandler;
use NgLamVN\InvCraft\command\InvCraftCommand;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase{
	/** @var Provider */
	public Provider $provider;
	/** @var Recipe[] */
	public array $recipes = [];

	public function onEnable() : void{
		if(!InvMenuHandler::isRegistered()){
			InvMenuHandler::register($this);
		}

		$this->provider = new Provider();
		$this->provider->open();

		$this->loadRecipes();

		$this->getServer()->getCommandMap()->register("invcraft", new InvCraftCommand($this));
	}

	public function loadRecipes(){
		$data = $this->getProvider()->getRecipesData();
		foreach(array_keys($data) as $recipe_name){
			$recipe_data = [];
			foreach($data[$recipe_name]["recipe"] as $item){
				array_push($recipe_data, ItemUtils::fromString($item));
			}
			$result = ItemUtils::fromString($data[$recipe_name]["result"]);
			if(!isset($data[$recipe_name]["mode"])){
				$mode = Recipe::VIxVI_MODE;
			}else $mode = $data[$recipe_name]["mode"];
			$recipe = Recipe::makeRecipe($recipe_name, $recipe_data, $result, $mode);
			$this->setRecipe($recipe);
		}
	}

	public function getProvider() : Provider{
		return $this->provider;
	}

	public function setRecipe(Recipe $recipe){
		$this->recipes[$recipe->getRecipeName()] = $recipe;
	}

	public function onDisable() : void{
		$this->saveRecipes();
		$this->getProvider()->save();
	}

	public function saveRecipes(){
		foreach($this->getRecipes() as $recipe){
			$data = [];
			$data["result"] = ItemUtils::toString($recipe->getResultItem());
			$recipe_data = [];
			foreach($recipe->getRecipeData() as $item){
				array_push($recipe_data, ItemUtils::toString($item));
			}
			$data["recipe"] = $recipe_data;
			$data["mode"] = $recipe->getMode();
			$this->getProvider()->setRecipeData($recipe->getRecipeName(), $data);
		}
	}

	/**
	 * @return Recipe[]
	 */
	public function getRecipes() : array{
		if(!isset($this->recipes)) return [];
		return $this->recipes;
	}

	/**
	 * @param string $name
	 * @return Recipe|null
	 *
	 */
	public function getRecipe(string $name) : ?Recipe{
		if(isset($this->recipes[$name])) return $this->recipes[$name];
		return null;
	}

	public function removeRecipe(Recipe $recipe){
		unset($this->recipes[$recipe->getRecipeName()]);
		$this->getProvider()->removeRecipeData($recipe->getRecipeName());
	}

}
