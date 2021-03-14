<?php

namespace NgLamVN\InvCraft\ui;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\SimpleForm;
use NgLamVN\InvCraft\Loader;
use NgLamVN\InvCraft\menu\AddRecipeMenu;
use NgLamVN\InvCraft\menu\CraftMenu;
use NgLamVN\InvCraft\menu\EditRecipeMenu;
use pocketmine\Player;
use pocketmine\Server;

class AdminUI
{
    public function __construct(Player $player)
    {
        $this->form($player);
    }

    /**
     * @return Loader|null
     */
    public function getLoader(): ?Loader
    {
        $loader = Server::getInstance()->getPluginManager()->getPlugin("InvCraft");
        if ($loader instanceof Loader)
        {
            return $loader;
        }
        return null;
    }

    public function form (Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data)
        {
            if (!isset($data))
            {
                return;
            }
            switch ($data)
            {
                case 0:
                    return new CraftMenu($player, $this->getLoader());
                    break;
                case 1:
                    $this->addRecipe($player);
                    break;
                case 2:
                    $this->editRecipe($player);
                    break;
                case 3:
                    $this->removeRecipe($player);
                    break;
            }
        });

        $form->setTitle("InvCraft Manager");
        $form->addButton("Craft Table");
        $form->addButton("Add Recipe");
        $form->addButton("Edit Recipe");
        $form->addButton("Remove Recipe");

        $player->sendForm($form);
    }

    public function addRecipe(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data)
        {
            if (!isset($data[0]))
            {
                return;
            }
            if (($data[0] == "") or ($data[0] == " "))
            {
                $player->sendMessage("Please enter a valid name");
                return;
            }
            foreach ($this->getLoader()->getRecipes() as $recipe)
            {
                if ($recipe->getRecipeName() == $data[0])
                {
                    $player->sendMessage("A Recipe with that name already exist !");
                    return;
                }
            }
            return new AddRecipeMenu($player, $this->getLoader(), $data[0]);
        });

        $form->setTitle("Add Recipe");
        $form->addInput("Recipe name:", "ABCabc123");

        $player->sendForm($form);
    }

    public function editRecipe (Player $player)
    {
        $recipes = [];
        foreach ($this->getLoader()->getRecipes() as $recipe)
        {
            array_push($recipes, $recipe);
        }

        $form = new SimpleForm(function (Player $player, $data) use ($recipes)
        {
            if (!isset($data))
            {
                return;
            }
            return new EditRecipeMenu($player, $this->getLoader(), $recipes[$data]);
        });

        $form->setTitle("Edit Recipe");
        foreach ($this->getLoader()->getRecipes() as $recipe)
        {
            $form->addButton($recipe->getRecipeName());
        }

        $player->sendForm($form);
    }

    public function removeRecipe(Player $player)
    {
        $recipes = [];
        foreach ($this->getLoader()->getRecipes() as $recipe)
        {
            array_push($recipes, $recipe);
        }

        $form = new SimpleForm(function (Player $player, $data) use ($recipes)
        {
            if (!isset($data))
            {
                return;
            }
            $re = $recipes[$data];

            $confirm = new ModalForm(function (Player $player, $data2) use ($re)
            {
                if (!isset($data2))
                {
                    return;
                }
                if ($data2 == true)
                {
                    $this->getLoader()->removeRecipe($re);
                    return;
                }
                return;
            });

            $confirm->setTitle("Confirm");
            $confirm->setButton1("YES");
            $confirm->setButton2("NO");
            $confirm->setContent("Are you want to remove this recipe ?");

            $player->sendForm($confirm);
        });

        $form->setTitle("Remove Recipe");
        foreach ($this->getLoader()->getRecipes() as $recipe)
        {
            $form->addButton($recipe->getRecipeName());
        }

        $player->sendForm($form);
    }
}
