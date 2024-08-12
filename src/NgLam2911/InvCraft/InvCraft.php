<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft;

use Generator;
use NgLam2911\InvCraft\libs\_522e88ace0fcd4c0\muqsit\invmenu\InvMenuHandler;
use NgLam2911\InvCraft\database\Database;
use NgLam2911\InvCraft\crafting\RecipeManager;
use pocketmine\plugin\PluginBase;
use NgLam2911\InvCraft\libs\_522e88ace0fcd4c0\SOFe\AwaitGenerator\Await;

class InvCraft extends PluginBase{

    protected Database $database;
    protected RecipeManager $recipeManager;

    protected function onEnable() : void{
        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }
        $this->database = new Database($this);
        $this->recipeManager = new RecipeManager($this);
        // Load recipes from database
        Await::f2c(function() : Generator{
            yield from $this->database->asyncLoad();
            $this->recipeManager->setReady();
        });
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