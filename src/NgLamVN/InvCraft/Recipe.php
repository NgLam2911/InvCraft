<?php

declare(strict_types=1);

namespace NgLamVN\InvCraft;

use pocketmine\item\Item;

class Recipe
{
    /** @var string $recipe_name */
    public $recipe_name;
    /** @var int $recipe_id */
    public $recipe_id;
    /** @var Item[] $recipe_data */
    public $recipe_data;

    //Press "F" for PHP 7.3 and below, I can't implement like this: public string $recipe_name; | It only work on PHP 7.4+

    public function __construct(string $recipe_name, int $recipe_id, array $recipe_data)
    {
        $this->recipe_name = $recipe_name;
        $this->recipe_data = $recipe_data;
        $this->recipe_id = $recipe_id;
    }

    /**
     * @param string $recipe_name
     * @param int $recipe_id
     * @param array $recipe_data
     * @return Recipe
     */
    public static function makeRecipe(string $recipe_name, int $recipe_id, array $recipe_data)
    {
        return new Recipe($recipe_name, $recipe_id, $recipe_data);
    }

    public static function equal(Recipe $recipe1, Recipe $recipe2): bool
    {
        if ($recipe1->getRecipeData() == $recipe2->getRecipeData())
        {
            return true;
        }
        else return false;
    }

    /**
     * @return Item[]
     */
    public function getRecipeData(): array
    {
        return $this->recipe_data;
    }

    /**
     * @return int
     */
    public function getRecipeId(): int
    {
        return $this->recipe_id;
    }

    /**
     * @return string
     */
    public function getRecipeName(): string
    {
        return $this->recipe_name;
    }

    /**
     * @param int $x
     * @param int $y
     * @return Item
     */
    public function getRecipeItem(int $x,int $y): Item
    {
        return $this->recipe_data[$x][$y];
    }

    /**
     * @param string $name
     */
    public function setRecipeName(string $name)
    {
        $this->recipe_name = $name;
    }

    /**
     * @param Item[] $data
     */
    public function setRecipeData(array $data)
    {
        $this->recipe_data = $data
    }

    /**
     * @param int $id
     */
    public function setRecipeId(int $id)
    {
        $this->recipe_id = $id;
    }

    /**
     * @param int $x
     * @param int $y
     * @param Item $item
     */
    public function setRecipeItem(int $x, int $y, Item $item)
    {
        $this->recipe_data[$x][$y] = $item;
    }
}
