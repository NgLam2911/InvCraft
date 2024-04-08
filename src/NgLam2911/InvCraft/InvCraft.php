<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft;

use NgLam2911\InvCraft\libs\_ddfba3c9bc07a3f1\muqsit\invmenu\InvMenuHandler;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;

class InvCraft extends PluginBase{

    protected function onEnable(): void{
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

    }
}