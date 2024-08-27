<?php
declare(strict_types = 1);

namespace NgLam2911\InvCraft\utils;

use pocketmine\nbt\tag\CompoundTag;

interface NbtSerializable {
    public function nbtSerialize() : CompoundTag;

    public static function nbtDeserialize(CompoundTag $tag) : self;
}