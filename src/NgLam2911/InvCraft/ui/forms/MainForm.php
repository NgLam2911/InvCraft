<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\ui\forms;

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use dktapps\pmforms\ModalForm;
use NgLam2911\InvCraft\crafting\Recipe;
use NgLam2911\InvCraft\InvCraft;
use NgLam2911\InvCraft\ui\gui\AddRecipeGUI;
use NgLam2911\InvCraft\ui\gui\CraftingGUI;
use NgLam2911\InvCraft\ui\gui\EditRecipeGUI;
use NgLam2911\InvCraft\ui\gui\ViewRecipeGUI;
use pocketmine\player\Player;

class MainForm{

    public function __construct(protected Player $player){}

    function sendForm() : void{
        $options = [
            new MenuOption("Crafting Table"),
            new MenuOption("List Recipes")
        ];
        $admin_options = [
            new MenuOption("Add Recipe"),
            new MenuOption("Edit existing Recipe"),
            new MenuOption("Remove Recipe")
        ];
        $title = "InvCraft Menu";
        $admin_title = "InvCraft Menu (Admin)";
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
            "Add Recipe",
            [
                new Input("recipe_name", "Enter Recipe Name", "abc123")
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
            "Remove Recipe",
            "Are you sure you want to remove this recipe ?",
            function (Player $player, bool $choice) use ($recipe) : void{
                if ($choice){
                    InvCraft::getInstance()->getRecipeManager()->removeRecipe($recipe->getName());
                }
            }
        );
        $player->sendForm($form);
    }
}