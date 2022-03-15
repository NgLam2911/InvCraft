<?php
declare(strict_types=1);

namespace NgLamVN\InvCraft\command;

use NgLamVN\InvCraft\Loader;
use NgLamVN\InvCraft\menu\ViewRecipe;
use NgLamVN\InvCraft\Recipe;
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
	 * @return void
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
		if(!$sender instanceof Player){
			$sender->sendMessage($this->getLoader()->getProvider()->getMessage("msg.runingame"));
			return;
		}
		if (!isset($args[0])){
			if($sender->hasPermission("ic.admin")){
				new AdminUI($sender);
				return;
			}
			new PlayerUI($sender);
		}
		if($args[0] == "view"){
			if(isset($args[1])){
				$name = $args[1];
				$recipe = $this->getLoader()->getRecipe($name);
				if(!$recipe instanceof Recipe){
					$sender->sendMessage($this->getLoader()->getProvider()->getMessage("command.recipenotfound"));
					return;
				}
				new ViewRecipe($sender, $this->getLoader(), $recipe);
			}else{
				$sender->sendMessage($this->getLoader()->getProvider()->getMessage("command.missrecipename"));
			}
		}
	}

	public function getLoader() : Loader{
		return $this->loader;
	}

	public function getOwningPlugin() : Plugin{
		return $this->getLoader();
	}
}