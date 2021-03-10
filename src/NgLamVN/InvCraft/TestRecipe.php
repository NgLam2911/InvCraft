<?php

namespace NgLamVN\InvCraft;

use pocketmine\item\Item;

class TestRecipe
{
    private $loader;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
        $this->genRecipe();
    }

    public function genRecipe()
    {
        $recipe_data = [
            Item::get(Item::SNOWBALL)->setCustomName("SNow 1"),
            Item::get(Item::SNOWBALL)->setCustomName("SNow 2"),
            Item::get(Item::SNOWBALL)->setCustomName("SNow 3"),
            Item::get(Item::SNOWBALL)->setCustomName("SNow 4"),
            Item::get(Item::SNOWBALL)->setCustomName("SNow 1"),
            Item::get(Item::SNOWBALL)->setCustomName("SNow 5"),
            Item::get(Item::SNOWBALL)->setCustomName("SNow 6"),
            Item::get(Item::SNOWBALL)->setCustomName("SNow 7"),
        ];
        $result = Item::get(Item::ANVIL);
        $result->setCustomName("TESTTTTTTTT");
        $recipe = Recipe::makeRecipe("testlol", $recipe_data, $result);
        $this->loader->setRecipe($recipe);
        $this->loader->getLogger()->info("RUNNED");
    }
}