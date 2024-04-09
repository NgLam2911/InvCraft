<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\recipe;

use Generator;
use NgLam2911\InvCraft\InvCraft;
use SOFe\AwaitGenerator\Await;

class RecipeManager{

    /** @var Recipe[] */
    private array $recipes = [];
    /**
     * @var bool
     * @description This method use for blocking player from crafting item before all recipes are loaded or updated
     * due to async nature of database
     */
    protected bool $ready = false;

    public function __construct(
        private readonly InvCraft $plugin
    ){}

    public function addRecipe(Recipe $recipe, $sync_with_db = true): void{
        $this->recipes[$recipe->getName()] = $recipe;
        if ($sync_with_db){
            $this->setReady(false);
            Await::f2c(function() use ($recipe) : Generator{
                yield $this->plugin->getDatabase()->asyncAdd($recipe);
                $this->setReady();
            });
        }
    }

    public function removeRecipe(string $name, $sync_with_db = true): void{
        if (!isset($this->recipes[$name])){
            return;
        }
        unset($this->recipes[$name]);
        if ($sync_with_db){
            $this->setReady(false);
            Await::f2c(function() use ($name) : Generator{
                yield $this->plugin->getDatabase()->asyncDelete($name);
                $this->setReady();
            });
        }
    }

    public function updateRecipe(Recipe $recipe, $sync_with_db = true): void{
        $this->recipes[$recipe->getName()] = $recipe;
        $this->setReady(false);
        if ($sync_with_db){
            Await::f2c(function() use ($recipe) : Generator{
                yield $this->plugin->getDatabase()->asyncUpdate($recipe);
                $this->setReady();
            });
        }
    }

    public function getRecipe(string $name): ?Recipe{
        return $this->recipes[$name] ?? null;
    }

    public function getRecipes(): array{
        return $this->recipes;
    }

    public function isReady(): bool{
        return $this->ready;
    }

    public function setReady(bool $ready = true): void{
        $this->ready = $ready;
    }
}