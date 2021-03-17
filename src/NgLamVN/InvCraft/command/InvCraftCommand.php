<?php

namespace NgLamVN\InvCraft\command;

use NgLamVN\InvCraft\Loader;
use NgLamVN\InvCraft\menu\CraftMenu;
use NgLamVN\InvCraft\ui\AdminUI;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginCommand;

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

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof ConsoleCommandSender)
        {
            $sender->sendMessage($this->getLoader()->getProvider()->getMessage("msg.runingame"));
            return;
        }
        if ($sender->hasPermission("ic.admin"))
        {
            return new AdminUI($sender);
        }
        return new CraftMenu($sender, $this->getLoader());
    }
}