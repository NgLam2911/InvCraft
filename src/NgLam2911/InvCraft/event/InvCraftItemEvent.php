<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\event;

use NgLam2911\InvCraft\crafting\CraftingGrid;
use NgLam2911\InvCraft\crafting\Recipe;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use pocketmine\player\Player;

class InvCraftItemEvent extends Event implements Cancellable {
    use CancellableTrait;

    public function __construct(
        protected Player $player,
        protected Recipe $recipe,
        protected CraftingGrid $grid
    ){}

    public function getPlayer() : Player{
        return $this->player;
    }

    public function getRecipe() : Recipe{
        return clone $this->recipe;
    }

    public function getGrid() : CraftingGrid{
        return clone $this->grid;
    }
}