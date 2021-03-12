<?php

namespace NgLamVN\InvCraft;

use muqsit\invmenu\InvMenuHandler;
use NgLamVN\InvCraft\command\InvCraftCommand;
use pocketmine\item\Item;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase
{
    /** @var Provider $provider */
    public $provider;
    /** @var Recipe $recipes */
    public $recipes = [];

    public function onEnable()
    {
        if(!InvMenuHandler::isRegistered())
        {
            InvMenuHandler::register($this);
        }

        $this->provider = new Provider($this->getDataFolder());
        $this->provider->open();

        $this->getServer()->getCommandMap()->register("invcraft", new InvCraftCommand($this));
    }

    public function onDisable()
    {
        $this->saveRecipes();
        $this->getProvider()->save();
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    public function loadRecipes()
    {
        $data = $this->getProvider()->getRecipesData();
        foreach (array_keys($data) as $recipe_name)
        {
            $recipe_data = [];
            foreach ($data[$recipe_name]["recipe"] as $item)
            {
                $nbt = BigEndianNBTStream::fromArray($item);
                $item = Item::nbtDeserialize($nbt);
                array_push($recipe_data, $item);
            }
            $result = Item::nbtDeserialize(BigEndianNBTStream::fromArray($data[$recipe_name]["result"]));

            $recipe = Recipe::makeRecipe($recipe_name, $recipe_data, $result);
            $this->setRecipe($recipe);
        }
    }

    public function saveRecipes()
    {
        foreach ($this->getRecipes() as $recipe)
        {
            $data = [];
            $data["result"] = BigEndianNBTStream::toArray($recipe->getResultItem()->nbtSerialize());
            $recipe_data = [];
            foreach ($recipe->getRecipeData() as $item)
            {
                $itemdata = BigEndianNBTStream::toArray($item->nbtSerialize());
                array_push($recipe_data, $itemdata);
            }
            $data["recipe"] = $recipe_data;
            $this->getProvider()->setRecipeData($recipe->getRecipeName(), $data);
        }
    }

    /**
     * @param string $name
     * @return Recipe
     */
    public function getRecipe(string $name): ?Recipe
    {
        if (isset($this->recipes[$name])) return $this->recipes[$name];
        return null;
    }

    /**
     * @return Recipe[]
     */
    public function getRecipes(): array
    {
        return $this->recipes;
    }

    public function setRecipe(Recipe $recipe)
    {
        $this->recipes[$recipe->getRecipeName()] = $recipe;
    }

}
