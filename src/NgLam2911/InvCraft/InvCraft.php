<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft;

use Generator;
use NgLam2911\InvCraft\libs\_7f24bd44278ec6ee\muqsit\invmenu\InvMenuHandler;
use NgLam2911\InvCraft\database\Database;
use NgLam2911\InvCraft\crafting\RecipeManager;
use NgLam2911\InvCraft\libs\_9fbc4bfefe0cd102\SOFe\AwaitGenerator\Await;
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
        // Load recipes from database
        Await::f2c(function() : Generator{
            yield $this->database->asyncLoad();
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