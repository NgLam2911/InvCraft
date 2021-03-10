<?php

namespace NgLamVN\InvCraft\command;

use NgLamVN\InvCraft\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;

class InvCraftCommand extends PluginCommand
{
    public $loader;

    public function __construct(Loader $loader)
    {
        parent::__construct("invcraft", $loader);

        $this->setDescription("InvCraft Command");
        $this->setPermission("ic.command");
    }

    public function getLoader(): Loader
    {
        return $this->loader;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        //TODO: execute
    }
}