<?php

namespace NgLamVN\InvCraft\ui;

use NgLamVN\InvCraft\Loader;
use NgLamVN\InvCraft\menu\CraftMenu;
use NgLamVN\InvCraft\menu\ViewRecipe;
use NgLamVN\InvCraft\Recipe;
use pocketmine\Server;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;

class PlayerUI
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
                    return new CraftMenu($player, $this->getLoader(), Recipe::VIxVI_MODE);
                case 1:
                    return new CraftMenu($player, $this->getLoader(), Recipe::IIIxIII_MODE);
                case 2:
                    $this->viewRecipe($player);
                    break;
            }
        });

        $form->setTitle($this->getLoader()->getProvider()->getMessage("ui.title.player"));
        $form->addButton($this->getLoader()->getProvider()->getMessage("ui.6x6recipe"));
        $form->addButton($this->getLoader()->getProvider()->getMessage("ui.3x3recipe"));
        $form->addButton($this->getLoader()->getProvider()->getMessage("ui.list"));

        $player->sendForm($form);
    }
    public function viewRecipe(Player $player)
    {
        $recipes = [];
        foreach ($this->getLoader()->getRecipes() as $recipe)
        {
            array_push($recipes, $recipe);
        }

        if ($recipes == [])
        {
            $player->sendMessage($this->getLoader()->getProvider()->getMessage("msg.norecipe"));
            return;
        }

        $form = new SimpleForm(function (Player $player, $data) use ($recipes)
        {
            if (!isset($data))
            {
                return;
            }
            return new ViewRecipe($player, $this->getLoader(), $recipes[$data]);
        });

        $form->setTitle($this->getLoader()->getProvider()->getMessage("ui.list"));
        foreach ($this->getLoader()->getRecipes() as $recipe)
        {
            $form->addButton($recipe->getRecipeName());
        }

        $player->sendForm($form);
    }
}
