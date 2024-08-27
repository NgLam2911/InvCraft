<?php
declare(strict_types = 1);

namespace NgLam2911\InvCraft\crafting\result;

use NgLam2911\InvCraft\utils\NbtSerializable;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

class TransferInfo implements NbtSerializable {

    public function __construct(
        private readonly int $slotX,
        private readonly int $slotY,
        /** @var $tags string[] */
        private array $tags
    ){}

    public function getTags() : array{
        return $this->tags;
    }

    public function removeTag(string $tag) : void{
        $key = array_search($tag, $this->tags);
        if($key !== false){
            unset($this->tags[$key]);
        }
    }

    public function addTag(string $tag) : void{
        if(!in_array($tag, $this->tags)){
            $this->tags[] = $tag;
        }
    }

    public function hasTag(string $tag) : bool{
        return in_array($tag, $this->tags);
    }

    public function getSlotX() : int{
        return $this->slotX;
    }

    public function getSlotY() : int{
        return $this->slotY;
    }

    public function nbtSerialize() : CompoundTag{
        $tag = new CompoundTag();
        $tag->setInt("SlotX", $this->slotX);
        $tag->setInt("SlotY", $this->slotY);
        $stringTags = [];
        foreach($this->tags as $value){
            $stringTags[] = new StringTag($value);
        }
        $tag->setTag("Tags", new ListTag($stringTags, NBT::TAG_String));
        return $tag;
    }

    public static function nbtDeserialize(CompoundTag $tag) : self{
        $slotX = $tag->getInt("SlotX");
        $slotY = $tag->getInt("SlotY");
        $tags = [];
        foreach($tag->getListTag("Tags") as $value){
            $tags[] = $value->getValue();
        }
        return new TransferInfo($slotX, $slotY, $tags);
    }
}