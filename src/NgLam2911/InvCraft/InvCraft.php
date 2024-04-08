<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft;

use NgLam2911\InvCraft\libs\_fa34e6fe9ee02ad5\muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;

class InvCraft extends PluginBase{

    protected function onEnable(): void{
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

    }
}