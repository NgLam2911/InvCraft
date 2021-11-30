<?php
declare(strict_types=1);

namespace NgLamVN\InvCraft\command;

use NgLamVN\InvCraft\Loader;
use NgLamVN\InvCraft\ui\AdminUI;
use NgLamVN\InvCraft\ui\PlayerUI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class InvCraftCommand extends Command implements PluginOwned{
	public Loader $loader;

	public function __construct(Loader $loader){
		$this->loader = $loader;
		parent::__construct("invcraft");
		$this->setDescription("InvCraft Command");
		$this->setPermission("ic.command");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return mixed
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
		if(!$sender instanceof Player){
			$sender->sendMessage($this->getLoader()->getProvider()->getMessage("msg.runingame"));
			return;
		}
		if($sender->hasPermission("ic.admin")){
			new AdminUI($sender);
			return;
		}
		new PlayerUI($sender);
	}

	public function getLoader() : Loader{
		return $this->loader;
	}

	public function getOwningPlugin() : Plugin{
		return $this->getLoader();
	}
}