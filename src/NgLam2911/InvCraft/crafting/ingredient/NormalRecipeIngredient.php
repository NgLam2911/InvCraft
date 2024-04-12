<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\crafting\ingredient;

use NgLam2911\InvCraft\utils\NbtSerializable;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;

class NormalRecipeIngredient implements RecipeIngredient{
    public function nbtSerialize(): CompoundTag
    {
        // TODO: Implement nbtSerialize() method.
    }

    public static function nbtDeserialize(CompoundTag $tag): self
    {
        // TODO: Implement nbtDeserialize() method.
    }

    public function accept(Item $item): bool
    {
        // TODO: Implement accept() method.
    }

    public function consume(Item $item): Item
    {
        // TODO: Implement consume() method.
    }
}