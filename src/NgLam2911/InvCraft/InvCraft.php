<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft;

use NgLam2911\InvCraft\libs\_4a2e51e1d5176dcf\muqsit\invmenu\InvMenuHandler;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;

class InvCraft extends PluginBase{

    protected function onEnable(): void{
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

    }
}