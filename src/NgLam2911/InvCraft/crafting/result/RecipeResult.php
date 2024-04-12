<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\crafting\result;

use NgLam2911\InvCraft\utils\NbtSerializable;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

class RecipeResult implements NbtSerializable {

    public function __construct(
        private Item $item,
        /** @var $transferInfos TransferInfo[] */
        private array $transferInfos = []
    ){}

    public function getItem() : Item {
        return clone $this->item;
    }

    public function getTransferInfos() : array {
        return $this->transferInfos;
    }

    public function addTransferInfo(TransferInfo $info) : void {
        $this->transferInfos[] = $info;
    }

    public function removeTransferInfo(TransferInfo $info) : void {
        $key = array_search($info, $this->transferInfos);
        if ($key !== false) {
            unset($this->transferInfos[$key]);
        }
    }

    public function removeTransferInfoBySlot(int $slotX, int $slotY) : void{
        foreach ($this->transferInfos as $key => $info) {
            if ($info->getSlotX() === $slotX && $info->getSlotY() === $slotY) {
                unset($this->transferInfos[$key]);
            }
        }
    }

    public function setResultItem(Item $item) : void{
        $this->item = $item;
    }

    public function nbtSerialize() : CompoundTag{
        $ctag = new CompoundTag();
        $ctag->setTag("Item", $this->item->nbtSerialize());
        $transferInfos = [];
        foreach ($this->transferInfos as $info){
            $transferInfos[] = $info->nbtSerialize();
        }
        $ctag->setTag("TransferInfos", new ListTag($transferInfos, NBT::TAG_Compound));
        return $ctag;
    }

    public static function nbtDeserialize(CompoundTag $tag) : self{
        $item = Item::nbtDeserialize($tag->getCompoundTag("Item"));
        $transferInfos = [];
        foreach ($tag->getListTag("TransferInfos") as $info){
            $transferInfos[] = TransferInfo::nbtDeserialize($info);
        }
        return new RecipeResult($item, $transferInfos);
    }
}