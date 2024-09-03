<?php
declare(strict_types = 1);

namespace NgLam2911\InvCraft;

use Generator;
use NgLam2911\InvCraft\libs\_6c9046632b65a4c3\muqsit\invmenu\InvMenuHandler;
use NgLam2911\InvCraft\command\InvCraftCommand;
use NgLam2911\InvCraft\crafting\RecipeManager;
use NgLam2911\InvCraft\database\Database;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use NgLam2911\InvCraft\libs\_6c9046632b65a4c3\SOFe\AwaitGenerator\Await;

class InvCraft extends PluginBase {
    use SingletonTrait;

    protected Database $database;
    protected RecipeManager $recipeManager;

    protected function onLoad() : void{
        self::setInstance($this);
    }

    protected function onEnable() : void{
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }
        $this->database = new Database();
        $this->recipeManager = new RecipeManager();
        // Load recipes from database
        Await::f2c(function() : Generator{
            yield from $this->database->asyncLoad();
            $this->recipeManager->setReady();
        });
        $this->getServer()->getCommandMap()->register("invcraft", new InvCraftCommand());
    }

    protected function onDisable() : void{
        $this->database->close();
    }

    public function getRecipeManager() : RecipeManager{
        return $this->recipeManager;
    }

    public function getDatabase() : Database{
        return $this->database;
    }
}