<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\recipe;

class RecipeManager{

    /** @var Recipe[] */
    private array $recipes = [];
    /**
     * @var bool
     * @description This method use for blocking player from crafting item before all recipes are loaded or updated
     * due to async nature of database
     */
    protected bool $ready = false;

    public function __construct(){}

    public function addRecipe(Recipe $recipe): void{
        //TODO: Sync with database
        $this->recipes[$recipe->getName()] = $recipe;
    }

    public function removeRecipe(string $name): void{
        //TODO: Sync with database
        unset($this->recipes[$name]);
    }

    public function updateRecipe(Recipe $recipe): void{
        //TODO: Sync with database
        $this->recipes[$recipe->getName()] = $recipe;
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