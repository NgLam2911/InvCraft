<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\forms;

use Closure;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use NgLam2911\InvCraft\InvCraft;
use NgLam2911\InvCraft\lang\LanguagesManager as Lang;
use NgLam2911\InvCraft\lang\TextKeys as Key;
use pocketmine\player\Player;

class ListRecipeForm{
    public function __construct(protected Player $player, protected ?Closure $todo = null){}

    function sendForm() : void{
        $recipes = array_values(InvCraft::getInstance()->getRecipeManager()->getRecipes());
        $title = Lang::getText(Key::FORM_LIST_TITLE);
        $options = [];
        foreach($recipes as $recipe){
            $options[] = new MenuOption($recipe->getName());
        }
        $form = new MenuForm(
            $title, "", $options,
            function(Player $submitter, int $selected) use ($recipes) : void{
                $recipe = $recipes[$selected];
                if (!is_null($this->todo)){
                    ($this->todo)($submitter, $recipe);
                }
            }
        );
        $this->player->sendForm($form);
    }
}