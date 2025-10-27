<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\lang;

use NgLam2911\InvCraft\InvCraft;
use pocketmine\utils\TextFormat;

class LanguagesManager {

    private const DEFAULT_LANGUAGE = "en-US";
    private const SUPPORTED_LANGUAGES = [
        "en-US"
    ];

    private static string $lang = self::DEFAULT_LANGUAGE;
    private static array $translations = [];

    public static function init(InvCraft $plugin, ?string $lang) : void{
        $langFolder = $plugin->getDataFolder() . "languages";
        if (!is_dir($langFolder)){
            mkdir($langFolder);
        }
        foreach (self::SUPPORTED_LANGUAGES as $langCode){
            $plugin->saveResource("languages" . DIRECTORY_SEPARATOR . $langCode . ".yml");
        }

        if (!$lang || !in_array($lang, self::SUPPORTED_LANGUAGES)){
            $lang = self::DEFAULT_LANGUAGE;
        }
        self::$lang = $lang;
        self::$translations = yaml_parse_file($langFolder . DIRECTORY_SEPARATOR . $lang . ".yml");
    }

    public static function getText(string $trans, array $vars = []) : string{
        return isset(self::$translations[$trans]) ?
            TextFormat::colorize(self::translate($trans, $vars)) :
            "Translation not found: " . $trans;
    }

    public static function getLanguage() : string{
        return self::$lang;
    }

    private static function translate(string $trans, array $vars = []) : string{
        return str_replace(array_keys($vars), array_values($vars), self::$translations[$trans]);
    }
}