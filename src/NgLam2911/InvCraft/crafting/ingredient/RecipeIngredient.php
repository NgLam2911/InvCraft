<?php
declare(strict_types = 1);

namespace NgLam2911\InvCraft\crafting\ingredient;

use NgLam2911\InvCraft\utils\NbtSerializable;
use pocketmine\item\Item;

interface RecipeIngredient extends NbtSerializable {

    /**
     * @return string
     * @description Get the type of this ingredient
     */
    public function getType() : string;

    /**
     * @param Item $item
     * @return bool
     * @description Check if the item can be accepted by this ingredient
     */
    public function accept(Item $item) : bool;

    /**
     * @param Item $item
     * @return Item
     * @description Return the result item after taking needed items for crafting
     */
    public function consume(Item $item) : Item;

    /**
     * @return Item
     * @description Get the item of this ingredient
     */
    public function getItem() : Item;
}