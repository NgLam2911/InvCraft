<?php

namespace NgLamVN\InvCraft\menu;

use NgLamVN\InvCraft\Loader;
use NgLamVN\InvCraft\Recipe;
use pocketmine\Player;

class EditRecipeMenu extends BaseMenu
{
    /** @var Recipe $recipe */
    public $recipe;

    public function __construct(Player $player, Loader $loader, Recipe $recipe)
    {
        parent::__construct($player, $loader);
        $this->recipe = $recipe;
    }

    //TODO: EditRecipeMenu
}
