<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\crafting\ingredient;

use NgLam2911\InvCraft\utils\NbtSerializable;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;

class EmptyRecipeIngredient implements RecipeIngredient{

    public function nbtSerialize() : CompoundTag{
        $ctag = new CompoundTag();
        $ctag->setString("type", "empty");
        return $ctag;
    }

    public static function nbtDeserialize(CompoundTag $tag) : NbtSerializable{
        return new self();
    }

    public function getType() : string{
        return "empty";
    }

    public function accept(Item $item) : bool{
        return $item->isNull();
    }

    public function consume(Item $item) : Item{
        return $item;
    }

    public function getItem() : Item{
        return VanillaItems::AIR();
    }
}