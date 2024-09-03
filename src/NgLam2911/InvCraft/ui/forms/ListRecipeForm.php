<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\forms;

use Closure;
use NgLam2911\InvCraft\libs\_6c9046632b65a4c3\dktapps\pmforms\MenuForm;
use NgLam2911\InvCraft\libs\_6c9046632b65a4c3\dktapps\pmforms\MenuOption;
use NgLam2911\InvCraft\InvCraft;
use pocketmine\player\Player;

class ListRecipeForm{
    public function __construct(protected Player $player, protected ?Closure $todo = null){}

    function sendForm() : void{
        $recipes = array_values(InvCraft::getInstance()->getRecipeManager()->getRecipes());
        $title = "List Recipes";
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