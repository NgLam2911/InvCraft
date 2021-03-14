<?php

declare(strict_types=1);

namespace NgLamVN\InvCraft;

use pocketmine\item\Item;
use pocketmine\Server;

class Recipe
{
    /** @var string $recipe_name */
    public $recipe_name;
    /** @var Item[] $recipe_data */
    public $recipe_data;
    /** @var Item $result */
    public $result;

    //Press "F" for PHP 7.3 and below, I can't implement like this: public string $recipe_name; | It only work on PHP 7.4+

    public function __construct(string $recipe_name, array $recipe_data, Item $result)
    {
        $this->recipe_name = $recipe_name;
        $this->recipe_data = $recipe_data;
        $this->result = $result;
    }

    public function getLoader(): ?Loader
    {
        return Server::getInstance()->getPluginManager()->getPlugin("InvCraft");
    }

    /**
     * @param string $recipe_name
     * @param Item[] $recipe_data
     * @param Item $result
     * @return Recipe
     */
    public static function makeRecipe(string $recipe_name, array $recipe_data, Item $result)
    {
        return new Recipe($recipe_name, $recipe_data, $result);
    }

    /**
     * @return Item[]
     */
    public function getRecipeData(): array
    {
        return $this->recipe_data;
    }

    /**
     * @return string
     */
    public function getRecipeName(): string
    {
        return $this->recipe_name;
    }

    /**
     * @param int $index
     * @return Item
     */
    public function getRecipeItem(int $index): Item
    {
        return $this->recipe_data[$index];
    }

    /**
     * @param string $name
     */
    public function setRecipeName(string $name)
    {
        $old = clone $this;
        $this->getLoader()->removeRecipe($old);
        $this->recipe_name = $name;
        $this->getLoader()->setRecipe($this);
    }

    /**
     * @param Item[] $data
     */
    public function setRecipeData(array $data)
    {
        $this->recipe_data = $data;
        $this->getLoader()->setRecipe($this);
    }

    /**
     * @param int $index
     * @param Item $item
     */
    public function setRecipeItem(int $index, Item $item)
    {
        $this->recipe_data[$index] = $item;
        $this->getLoader()->setRecipe($this);
    }

    /**
     * @return Item
     */
    public function getResultItem(): Item
    {
        return $this->result;
    }

    /**
     * @param Item $item
     */
    public function setResultItem(Item $item)
    {
        $this->result = $item;
        $this->getLoader()->setRecipe($this);
    }
}
