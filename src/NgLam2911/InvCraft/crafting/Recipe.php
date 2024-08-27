<?php
declare(strict_types = 1);

namespace NgLam2911\InvCraft\crafting;

use InvalidArgumentException;
use NgLam2911\InvCraft\crafting\ingredient\NormalRecipeIngredient;
use NgLam2911\InvCraft\crafting\ingredient\RecipeIngredient;
use NgLam2911\InvCraft\crafting\result\RecipeResult;
use NgLam2911\InvCraft\utils\NbtSerializable;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

class Recipe implements NbtSerializable {

    public function __construct(
        protected string $name,
        protected int $width,
        protected int $height,
        /** @var $ingredients RecipeIngredient[] */
        protected array $ingredients,
        protected RecipeResult $result
    ){}

    public function getName() : string{
        return $this->name;
    }

    public function getWidth() : int{
        return $this->width;
    }

    public function getHeight() : int{
        return $this->height;
    }

    public function getIngredient(int $x, int $y) : ?RecipeIngredient{
        if($x > $this->width || $x < 0 || $y > $this->height || $y < 0){
            throw new InvalidArgumentException("Invalid coordinate");
        }
        if(!isset($this->ingredients[$y][$x])){
            return null;
        }
        return $this->ingredients[$x][$y];
    }

    public function setIngredient(int $x, int $y, RecipeIngredient $ingredient) : void{
        if($x > $this->width || $x < 0 || $y > $this->height || $y < 0){
            throw new InvalidArgumentException("Invalid coordinate");
        }
        $this->ingredients[$y][$x] = $ingredient;
    }

    /**
     * @return array<int, array<int, Item>>
     */
    public function getIngredients() : array{
        return $this->ingredients;
    }

    public function getResult() : RecipeResult{
        return $this->result;
    }

    public function setResult(RecipeResult $result) : void{
        $this->result = $result;
    }

    /** @note a bit modified copy-pasta from PMMP code because it's good */
    public function matchInputMap(CraftingGrid $grid, $reverse = false) : bool{
        for ($y = 0; $y < $this->height; $y++){
            for ($x = 0; $x < $this->width; $x++){
                $given = $grid->getIngredient($reverse ? $this->width - $x - 1 : $x, $y);
                $required = $this->getIngredient($x, $y);

                if ($required === null){
                    if (!$given->isNull()){
                        return false;
                    }
                }elseif(!$required->accept($given)){
                    return false;
                }
            }
        }
        return true;
    }

    public function matchCraftingGrid(CraftingGrid $grid) : bool{
        if ($grid->getRecipeWidth() !== $this->width || $grid->getRecipeHeight() !== $this->height){
            return false;
        }
        return $this->matchInputMap($grid) || $this->matchInputMap($grid, true);
    }

    public function nbtSerialize() : CompoundTag{
        $ctag = new CompoundTag();
        $ctag->setString("name", $this->name);
        $ctag->setInt("width", $this->width);
        $ctag->setInt("height", $this->height);
        $ctag->setTag("result", $this->result->nbtSerialize());
        $ingredients = [];
        foreach($this->ingredients as $row){
            foreach($row as $item){
                $ingredients[] = $item->nbtSerialize();
            }
        }
        $itag = new ListTag($ingredients, NBT::TAG_Compound);
        $ctag->setTag("ingredients", $itag);
        return $ctag;
    }

    public static function nbtDeserialize(CompoundTag $tag) : self{
        $name = $tag->getString("name");
        $width = $tag->getInt("width");
        $height = $tag->getInt("height");
        $result = RecipeResult::nbtDeserialize($tag->getCompoundTag("result"));
        $ingredients = [];
        $itag = $tag->getListTag("ingredients");
        $data = $itag->getValue();
        $index = 0;
        for($y = 0; $y < $height; ++$y){
            for($x = 0; $x < $width; ++$x){
                if($data[$index] instanceof CompoundTag){
                    $ingredient_type = $data[$index]->getString("type");
                    $ingredients[$y][$x] = match ($ingredient_type) {
                        "normal" => NormalRecipeIngredient::nbtDeserialize($data[$index]),
                        default => null
                    };
                }
                $index++;
            }
        }
        return new self($name, $width, $height, $ingredients, $result);
    }

    public static function fromCraftingGrid(string $name, CraftingGrid $grid, RecipeResult $result) : self{
        $ingredients = [];
        for($y = 0; $y < $grid->getRecipeHeight(); ++$y){
            for($x = 0; $x < $grid->getRecipeWidth(); ++$x){
                $ingredients[$y][$x] = new NormalRecipeIngredient($grid->getIngredient($x, $y));
            }
        }
        return new self($name, $grid->getRecipeWidth(), $grid->getRecipeHeight(), $ingredients, $result);
    }
}