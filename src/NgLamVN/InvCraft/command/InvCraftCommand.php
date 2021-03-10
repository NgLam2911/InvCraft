<?php

namespace NgLamVN\InvCraft\command;

use NgLamVN\InvCraft\Loader;
use NgLamVN\InvCraft\TestRecipe;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;

class InvCraftCommand extends PluginCommand
{
    public $loader;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
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
        new TestRecipe($this->getLoader());
    }
}