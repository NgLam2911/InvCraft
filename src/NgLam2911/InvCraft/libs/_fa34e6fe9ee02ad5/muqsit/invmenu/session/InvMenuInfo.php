<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_fa34e6fe9ee02ad5\muqsit\invmenu\session;

use NgLam2911\InvCraft\libs\_fa34e6fe9ee02ad5\muqsit\invmenu\InvMenu;
use NgLam2911\InvCraft\libs\_fa34e6fe9ee02ad5\muqsit\invmenu\type\graphic\InvMenuGraphic;

final class InvMenuInfo{

	public function __construct(
		readonly public InvMenu $menu,
		readonly public InvMenuGraphic $graphic,
		readonly public ?string $graphic_name
	){}
}