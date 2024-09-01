<?php
declare(strict_types = 1);

namespace NgLam2911\InvCraft\crafting;

use Closure;
use Generator;
use NgLam2911\InvCraft\InvCraft;
use NgLam2911\InvCraft\libs\_a3643b0ab26a7681\SOFe\AwaitGenerator\Await;

class RecipeManager {

    /** @var Recipe[] */
    private array $recipes = [];
    /**
     * @var bool
     * @description This method use for blocking player from crafting item before all recipes are loaded or updated
     * due to async nature of database
     */
    protected bool $ready = false;

    public function __construct(
    ){}

    public function addRecipe(Recipe $recipe, $sync_with_db = true, Closure $callback = null) : void{
        $this->recipes[$recipe->getName()] = $recipe;
        if($sync_with_db){
            $this->setReady(false);
            Await::f2c(function() use ($recipe, $callback) : Generator{
                yield from InvCraft::getInstance()->getDatabase()->asyncAdd($recipe);
                $this->setReady();
                if ($callback !== null){
                    $callback();
                }
            });
        }
    }

    public function removeRecipe(string $name, $sync_with_db = true, Closure $callback = null) : void{
        if(!isset($this->recipes[$name])){
            return;
        }
        unset($this->recipes[$name]);
        if($sync_with_db){
            $this->setReady(false);
            Await::f2c(function() use ($name, $callback) : Generator{
                yield from InvCraft::getInstance()->getDatabase()->asyncDelete($name);
                $this->setReady();
                if ($callback !== null){
                    $callback();
                }
            });
        }
    }

    public function updateRecipe(Recipe $recipe, $sync_with_db = true, Closure $callback = null) : void{
        $this->recipes[$recipe->getName()] = $recipe;
        $this->setReady(false);
        if($sync_with_db){
            Await::f2c(function() use ($recipe, $callback) : Generator{
                yield from InvCraft::getInstance()->getDatabase()->asyncUpdate($recipe);
                $this->setReady();
                if ($callback !== null){
                    $callback();
                }
            });
        }
    }

    public function matchCraftingGrid(CraftingGrid $grid) : ?Recipe{
        foreach($this->recipes as $recipe){
            if($recipe->matchCraftingGrid($grid)){
                return $recipe;
            }
        }
        return null;
    }

    public function getRecipe(string $name) : ?Recipe{
        return $this->recipes[$name] ?? null;
    }

    public function getRecipes() : array{
        return $this->recipes;
    }

    public function isReady() : bool{
        return $this->ready;
    }

    public function setReady(bool $ready = true) : void{
        $this->ready = $ready;
    }
}