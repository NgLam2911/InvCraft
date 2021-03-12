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
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
        ];
        $result = Item::get(Item::ANVIL);
        $result->setCustomName("TESTTTTTTTT");
        $recipe = Recipe::makeRecipe("testlol", $recipe_data, $result);
        $this->loader->setRecipe($recipe);
        $recipe_data2 = [
            Item::get(Item::SNOWBALL)->setCustomName("YEPLOL"),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
            Item::get(Item::SNOWBALL),
        ];

        if ($recipe_data == $recipe_data2) $this->loader->getLogger()->info("TRUEE");
        else $this->loader->getLogger()->info("FALSE (it runned perfect.)");
    }
}