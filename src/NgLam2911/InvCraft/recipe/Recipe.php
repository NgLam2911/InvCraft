<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\recipe;

use InvalidArgumentException;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

class Recipe{

    public function __construct(
        protected string $name,
        protected int $width,
        protected int $height,
        protected array $ingredients,
        protected Item $result,
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

    public function getIngredient(int $x, int $y): Item{
        if ($x > $this->width || $x < 0 || $y > $this->height || $y < 0) {
            throw new InvalidArgumentException("Invalid coordinate");
        }
        if (!isset($this->ingredients[$y][$x])) {
            return VanillaItems::AIR();
        }
        return $this->ingredients[$x][$y];
    }

    public function setIngredient(int $x, int $y, Item $item): void{
        if ($x > $this->width || $x < 0 || $y > $this->height || $y < 0) {
            throw new InvalidArgumentException("Invalid coordinate");
        }
        $this->ingredients[$y][$x] = $item;
    }

    /**
     * @return array<int, array<int, Item>>
     */
    public function getIngredients(): array{
        return $this->ingredients;
    }

    public function getResult(): Item{
        return $this->result;
    }

    public function setResult(Item $item): void{
        $this->result = $item;
    }

    public function match(CraftingGrid $grid): bool{
        if ($grid->getWidth() < $this->width || $grid->getHeight() < $this->height) {
            return false;
        }
        for ($y = 0; $y < $this->height; ++$y) {
            for ($x = 0; $x < $this->width; ++$x) {
                if (!$this->getIngredient($x, $y)->equals($grid->getItem($x, $y))) {
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

    public static function nbtDeserialize(CompoundTag $ctag): self{
        $name = $ctag->getString("name");
        $width = $ctag->getInt("width");
        $height = $ctag->getInt("height");
        $result = Item::nbtDeserialize($ctag->getCompoundTag("result"));
        $ingredients = [];
        $itag = $ctag->getListTag("ingredients");
        $data = $itag->getValue();
        $index = 0;
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                if ($data[$index] instanceof CompoundTag){
                    $ingredients[$y][$x] = Item::nbtDeserialize($data[$index]);
                }
                $index++;
            }
        }
        return new self($name, $width, $height, $ingredients, $result);
    }
}