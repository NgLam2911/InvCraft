<?php

namespace NgLamVN\InvCraft\command;

use NgLamVN\InvCraft\Loader;
use NgLamVN\InvCraft\menu\AddRecipeMenu;
use NgLamVN\InvCraft\menu\CraftMenu;
use NgLamVN\InvCraft\TestRecipe;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
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
        if ($sender instanceof ConsoleCommandSender)
        {
            return new TestRecipe($this->getLoader());
        }
        if (isset($args[0]))
        {
            if ($args[0] == "add")
            {
                return new AddRecipeMenu($sender, $this->getLoader(), "add");
            }
        }

        return new CraftMenu($sender, $this->getLoader());
    }
}