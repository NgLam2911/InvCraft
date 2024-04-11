<?php
declare(strict_types=1);

namespace NgLam2911\InvCraft\crafting;

use pocketmine\item\Item;

class CraftingGrid{

    public function __construct(
        protected int $width,
        protected int $height,
        protected array $items,
    ){}

    public function getWidth(): int{
        return $this->width;
    }

    public function getHeight(): int{
        return $this->height;
    }

    public function getItem(int $x, int $y): ?Item{
        if ($x > $this->width || $x < 0 || $y > $this->height || $y < 0) {
            return null;
        }
        return $this->items[$y][$x] ?? null;
    }

    public function setItem(int $x, int $y, Item $item): void{
        if ($x > $this->width || $x < 0 || $y > $this->height || $y < 0) {
            return;
        }
        $this->items[$y][$x] = $item;
    }

    /**
     * @return array<int, array<int, Item>>
     */
    public function getItems(): array{
        return $this->items;
    }
}
