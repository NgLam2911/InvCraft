# Invcraft - A 6x6 crafting table plugin for PocketMine-MP
Feel free to report any bugs or suggest new features in [Issues](https://github.com/NgLam2911/InvCraft/issues)

# Command & Permission
## Command
- `/invcraft` - Open InvCraft main menu
  - aliases: `/ic`
  - permission: `ic.command`
## Permission
- `ic.command` - Allow player to use `/invcraft` command including view and craft items
- `ic.admin` - Allow player to use add, edit, remove recipes (will show additional options in menu for admin)

# News in this version
- [X] MySQL, SQLite database support.
- [X] Multi-pattern matching like vanilla crafting table.
- [X] Work with any items, including custom items (if you have a plugin that can create custom items).
- [X] Languages support

# Planned for future update
- [ ] Custom ingredients, result process
  - [ ] Any meta match, EX: Any planks
  - [ ] Transfer NBT from ingredients to result
- [ ] Custom, advanced API for developers
- [X] I'm too lazy.

And more...

# Build from s a u c e
You can use pharynx to build this plugin, if pharynx is in your server path and you have cloned this repo into your plugins folder, you can use following cmd:
```bash
php -dphar.readonly=0 pharynx.phar -c -i plugins/InvCraft -p=plugins/InvCraft.phar
```
or you can find the lastest build in [Poggit](https://poggit.pmmp.io/ci/NgLam2911/InvCraft/InvCraft)
### This rewrite version is NOT compatible with the old version, i currently dont have any idea for converting old recipes to the new format, so you have to create them again.

# For developers (API Doc)
## Recipes API
```php
use NgLam2911\InvCraft\crafting\Recipe;
use NgLam2911\InvCraft\InvCraft;

$recipeManager = InvCraft::getInstance()->getRecipeManager();
// Create a new recipe
$recipe = new Recipe(<args here>);
$recipeManager->addRecipe($recipe);
// Remove a recipe
$recipeManager->removeRecipe(<recipe name>);
// Get a recipe
$result = $recipeManager->getRecipe(<recipe name>);
```
## Handle when a player craft an item
```php
use NgLam2911\InvCraft\event\InvCraftItemEvent;

// EventsListener.php
public function onCraft(InvCraftItemEvent $event) {
    $player = $event->getPlayer();
    $recipe = $event->getRecipe();
    $result = $event->getResult();
    // Do something here
}
```
