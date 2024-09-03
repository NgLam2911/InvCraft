<?php

declare(strict_types=1);

namespace NgLam2911\InvCraft\libs\_2d7daee07e06bfc7\muqsit\invmenu\session;

use NgLam2911\InvCraft\libs\_2d7daee07e06bfc7\muqsit\invmenu\InvMenu;
use NgLam2911\InvCraft\libs\_2d7daee07e06bfc7\muqsit\invmenu\type\graphic\InvMenuGraphic;

final class InvMenuInfo{

	public function __construct(
		readonly public InvMenu $menu,
		readonly public InvMenuGraphic $graphic,
		readonly public ?string $graphic_name
	){}
}