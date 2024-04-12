<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\crafting;

use InvalidArgumentException;
use NgLam2911\InvCraft\crafting\ingredient\RecipeIngredient;
use NgLam2911\InvCraft\crafting\result\RecipeResult;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use NgLam2911\InvCraft\utils\NbtSerializable;

class Recipe implements NbtSerializable{

    public function __construct(
        protected string $name,
        protected int $width,
        protected int $height,
        /** @var $ingredients RecipeIngredient[] */
        protected array $ingredients,
        protected RecipeResult $result,
    ){}

    public function getName(): string{
        return $this->name;
    }

    public function getWidth(): int{
        return $this->width;
    }

    public function getHeight(): int{
        return $this->height;
    }

    public function getIngredient(int $x, int $y): ?RecipeIngredient{
        if ($x > $this->width || $x < 0 || $y > $this->height || $y < 0) {
            throw new InvalidArgumentException("Invalid coordinate");
        }
        if (!isset($this->ingredients[$y][$x])) {
            return null;
        }
        return $this->ingredients[$x][$y];
    }

    public function setIngredient(int $x, int $y, RecipeIngredient $ingredient): void{
        if ($x > $this->width || $x < 0 || $y > $this->height || $y < 0) {
            throw new InvalidArgumentException("Invalid coordinate");
        }
        $this->ingredients[$y][$x] = $ingredient;
    }

    /**
     * @return array<int, array<int, Item>>
     */
    public function getIngredients(): array{
        return $this->ingredients;
    }

    public function getResult(): RecipeResult{
        return $this->result;
    }

    public function setResult(RecipeResult $result): void{
        $this->result = $result;
    }

    public function match(CraftingGrid $grid): bool{
        if ($grid->getWidth() < $this->width || $grid->getHeight() < $this->height) {
            return false;
        }
        for ($y = 0; $y < $this->height; ++$y) {
            for ($x = 0; $x < $this->width; ++$x) {
                if (!$this->getIngredient($x, $y)->accept($grid->getItem($x, $y))) {
                    return false;
                }
            }
        }
        return true;
    }

    public function nbtSerialize(): CompoundTag{
        $ctag = new CompoundTag();
        $ctag->setString("name", $this->name);
        $ctag->setInt("width", $this->width);
        $ctag->setInt("height", $this->height);
        $ctag->setTag("result", $this->result->nbtSerialize());
        $ingredients = [];
        foreach ($this->ingredients as $row) {
            foreach ($row as $item) {
                $ingredients[] = $item->nbtSerialize();
            }
        }
        $itag = new ListTag($ingredients, NBT::TAG_Compound);
        $ctag->setTag("ingredients", $itag);
        return $ctag;
    }

    public static function nbtDeserialize(CompoundTag $tag): self{
        $name = $tag->getString("name");
        $width = $tag->getInt("width");
        $height = $tag->getInt("height");
        $result = RecipeResult::nbtDeserialize($tag->getCompoundTag("result"));
        $ingredients = [];
        $itag = $tag->getListTag("ingredients");
        $data = $itag->getValue();
        $index = 0;
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                if ($data[$index] instanceof CompoundTag){
                    $ingredients[$y][$x] = RecipeIngredient::nbtDeserialize($data[$index]);
                    //TODO: Fix this
                }
                $index++;
            }
        }
        return new self($name, $width, $height, $ingredients, $result);
    }
}