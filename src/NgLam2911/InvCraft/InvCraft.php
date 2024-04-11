<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft;

use NgLam2911\InvCraft\libs\_b85e8bb11fb3a7ce\muqsit\invmenu\InvMenuHandler;
use NgLam2911\InvCraft\database\Database;
use NgLam2911\InvCraft\recipe\RecipeManager;
use pocketmine\plugin\PluginBase;

class InvCraft extends PluginBase{

    protected Database $database;
    protected RecipeManager $recipeManager;

    protected function onEnable() : void{
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }
        $this->database = new Database($this);
        $this->recipeManager = new RecipeManager($this);
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