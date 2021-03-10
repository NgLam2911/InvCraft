<?php

declare(strict_types=1);

namespace NgLamVN\InvCraft;

use pocketmine\utils\Config;

class Provider
{
    /** @var string $path*/
    private $path;
    /** @var Config $config */
    public $config;
    /** @var array $recipes */
    public $recipes;

    //YamlProvider "I am noob at MySQL or SQLite"

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function open()
    {
        $this->config = new Config($this->path . "recipes.yml", Config::YAML);
        $this->recipes = $this->config->getAll();
    }

    public function save()
    {
        $this->config->setAll($this->recipes);
        $this->save();
    }

    public function getRecipesData(): array
    {
        return $this->recipes;
    }

    public function getRecipeData(string $name): ?array
    {
        if (isset($this->recipes[$name])) return $this->recipes[$name];
        return null;
    }

    public function setRecipesData (array $recipes): void
    {
        $this->recipes = $recipes;
    }

    public function setRecipeData (string $name, array $data)
    {
        $this->recipes[$name] = $data;
    }

}