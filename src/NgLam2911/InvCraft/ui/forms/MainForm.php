<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\forms;

use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\dktapps\pmforms\CustomForm;
use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\dktapps\pmforms\CustomFormResponse;
use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\dktapps\pmforms\element\Input;
use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\dktapps\pmforms\MenuForm;
use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\dktapps\pmforms\MenuOption;
use NgLam2911\InvCraft\libs\_9b30eaebc45eebb1\dktapps\pmforms\ModalForm;
use NgLam2911\InvCraft\crafting\Recipe;
use NgLam2911\InvCraft\InvCraft;
use NgLam2911\InvCraft\lang\LanguagesManager as Lang;
use NgLam2911\InvCraft\lang\TextKeys as Key;
use NgLam2911\InvCraft\ui\gui\AddRecipeGUI;
use NgLam2911\InvCraft\ui\gui\CraftingGUI;
use NgLam2911\InvCraft\ui\gui\EditRecipeGUI;
use NgLam2911\InvCraft\ui\gui\ViewRecipeGUI;
use pocketmine\player\Player;

class MainForm{

    public function __construct(protected Player $player){}

    function sendForm() : void{
        $options = [
            new MenuOption(Lang::getText(Key::FORM_MENU_CRAFT)),
            new MenuOption(Lang::getText(Key::FORM_MENU_LIST))
        ];
        $admin_options = [
            new MenuOption(Lang::getText(Key::FORM_MENU_ADD)),
            new MenuOption(Lang::getText(Key::FORM_MENU_EDIT)),
            new MenuOption(Lang::getText(Key::FORM_MENU_REMOVE))
        ];
        $title = Lang::getText(Key::FORM_MENU_TITLE);
        $admin_title = Lang::getText(Key::FORM_MENU_TITLE_ADMIN);
        if ($this->player->hasPermission("ic.admin")){
            $options = array_merge($options, $admin_options);
            $title = $admin_title;
        }

        $form = new MenuForm(
            $title, "", $options,
            function(Player $submitter, int $selected) : void{
                switch($selected){
                    case 0:
                        (new CraftingGUI($submitter))->sendToPlayer();
                        break;
                    case 1:
                        (new ListRecipeForm($submitter, function (Player $player, Recipe $recipe){
                            (new ViewRecipeGUI($player, $recipe))->sendToPlayer();
                        }))->sendForm();
                        break;
                    case 2:
                        $this->nameForm($submitter);
                        break;
                    case 3:
                        (new ListRecipeForm($submitter, function (Player $player, Recipe $recipe){
                            (new EditRecipeGUI($player, $recipe))->sendToPlayer();
                        }))->sendForm();
                        break;
                    case 4:
                        (new ListRecipeForm($submitter, function (Player $player, Recipe $recipe){
                            $this->confirmForm($player, $recipe);
                        }))->sendForm();
                        break;
                }
            }
        );
        $this->player->sendForm($form);
    }

    private function nameForm(Player $player) : void{
        $form = new CustomForm(
            Lang::getText(Key::FORM_ADD_TITLE),
            [
                new Input("recipe_name", Lang::getText(Key::FORM_ADD_TEXT), "abc123")
            ],
            function (Player $player, CustomFormResponse $response) : void{
                $recipe_name = $response->getString("recipe_name");
                (new AddRecipeGUI($player, $recipe_name))->sendToPlayer();
            }
        );
        $player->sendForm($form);
    }

    private function confirmForm(Player $player, Recipe $recipe) : void{
        $form = new ModalForm(
            Lang::getText(Key::FORM_REMOVE_TITLE),
            Lang::getText(Key::FORM_REMOVE_TEXT),
            function (Player $player, bool $choice) use ($recipe) : void{
                if ($choice){
                    InvCraft::getInstance()->getRecipeManager()->removeRecipe($recipe->getName());
                }
            }
        );
        $player->sendForm($form);
    }
}