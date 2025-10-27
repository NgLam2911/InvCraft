<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\command;

use NgLam2911\InvCraft\InvCraft;
use NgLam2911\InvCraft\ui\forms\MainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use NgLam2911\InvCraft\lang\LanguagesManager as Lang;
use NgLam2911\InvCraft\lang\TextKeys as Key;

class InvCraftCommand extends Command implements PluginOwned {

    public function __construct(){
        parent::__construct("invcraft");
        $this->setPermission("ic.command");
        $this->setDescription(Lang::getText(Key::COMMAND_DESCRIPTION));
        $this->setUsage("/invcraft");
        $this->setAliases(["ic"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if (!$sender instanceof Player){
            $sender->sendMessage(Lang::getText(Key::COMMAND_INGAME));
            return;
        }
        if (InvCraft::getInstance()->getRecipeManager()->isReady()){
            (new MainForm($sender))->sendForm();
        } else {
            $sender->sendMessage(Lang::getText(Key::COMMAND_WAIT));
        }
    }

    public function getOwningPlugin() : Plugin{
        return InvCraft::getInstance();
    }
}