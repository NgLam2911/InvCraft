<?php

declare(strict_types=1);

namespace NgLamVN\InvCraft;

use JackMD\ConfigUpdater\ConfigUpdater;
use JsonException;
use pocketmine\Server;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\item\StringToItemParser;
use pocketmine\utils\Config;
use pocketmine\item\Item;

class Provider{
	/** @var Config $config */
	public Config $config;
	/** @var array $recipes */
	public array $recipes;
	/** @var Config */
	public Config $msg;

	private const CONFIG_VERSION = 1;

	//YamlProvider "I am noob at MySQL or SQLite"

	public function __construct(){
		//NOTHING.
	}

	public function open(){
		$this->config = new Config($this->getLoader()->getDataFolder() . "recipes.yml", Config::YAML);
		$this->recipes = $this->config->getAll();
		$this->getLoader()->saveResource("message.yml");
		$this->msg = new Config($this->getLoader()->getDataFolder() . "message.yml", Config::YAML);
		//Check update
		if (ConfigUpdater::checkUpdate($this->getLoader(), $this->msg, "config-version", self::CONFIG_VERSION)){
			$this->msg = new Config($this->getLoader()->getDataFolder() . "message.yml", Config::YAML);
		}
	}
	public function stringToItem(string $input): ?Item {
        $string = strtolower(str_replace([' ', 'minecraft:'], ['_', ''], trim($input)));
        try {
            $item = StringToItemParser::getInstance()->parse($string) ?? LegacyStringToItemParser::getInstance()->parse($string);
        } catch (LegacyStringToItemParserException $e) {
            return null;
        }
        return $item;
    }

	public function getLoader() : ?Loader{
		$loader = Server::getInstance()->getPluginManager()->getPlugin("InvCraft");
		if($loader instanceof Loader){
			return $loader;
		}
		return null;
	}

	/**
	 * @throws JsonException
	 */
	public function save(){
		$this->config->setAll($this->recipes);
		$this->config->save();
	}

	public function getRecipesData() : array{
		return $this->recipes;
	}

	public function getRecipeData(string $name) : ?array{
		if(isset($this->recipes[$name])) return $this->recipes[$name];
		return null;
	}

	public function setRecipesData(array $recipes) : void{
		$this->recipes = $recipes;
	}

	public function setRecipeData(string $name, array $data){
		$this->recipes[$name] = $data;
	}

	public function removeRecipeData(string $name){
		unset($this->recipes[$name]);
	}

	public function getMessage(string $msg){
		return $this->msg->get($msg);
	}

}
