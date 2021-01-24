<?php

namespace NgLamVN\InvCraft;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase
{
    public function onEnable()
    {
        if(!InvMenuHandler::isRegistered())
        {
            InvMenuHandler::register($this);
        }


    }

    public function onDisable()
    {
    }

    /**
     * @param array $data
     * @return Item[]
     */
    public function makeItemList (array $data): array
    {
        foreach (array_keys($data) as $id)
        {
            //TODO: Make Items Function
        }
    }

    public function ArrayToCompoundTag(array $array): CompoundTag
    {
        $nbt = new CompoundTag();
    }

    public function CompoundTagToArray(CompoundTag $nbt): array
    {
        foreach ($nbt as $tag)
        {

        }
    }

    /**
     * @param string $id
     * @param CompoundTag $nbt
     * @return Item
     */
    public function makeItem(string $id, CompoundTag $nbt): Item
    {
        $info = explode($id, ":");
        $item = Item::get($info[0], $info[1], $info[2]);
        $item->setNamedTag($nbt);
        return $item;
    }
}
