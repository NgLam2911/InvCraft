<?php
declare(strict_types = 1);

namespace NgLam2911\InvCraft\crafting\ingredient;

use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;

class NormalRecipeIngredient implements RecipeIngredient {

    public function __construct(
        protected Item $item
    ){}

    public function nbtSerialize() : CompoundTag{
        $ctag = new CompoundTag();
        $ctag->setTag("item", $this->item->nbtSerialize());
        $ctag->setString("type", $this->getType());
        return $ctag;
    }

    public static function nbtDeserialize(CompoundTag $tag) : self{
        return new self(Item::nbtDeserialize($tag->getCompoundTag("item")));
    }

    public function getType() : string{
        return "normal";
    }

    public function accept(Item $item) : bool{
        return $item->canStackWith($this->item) && $item->getCount() >= $this->item->getCount();
    }

    public function consume(Item $item) : Item{
        $item->setCount($item->getCount() - $this->item->getCount());
        return $item;
    }

    public function getItem() : Item{
        return $this->item;
    }
}