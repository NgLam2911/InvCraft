<?php

declare(strict_types=1);

namespace GreenJajot\CustomCraftUltimate;

use GreenJajot\CustomCraftUltimate\libs\jojoe77777\FormAPI\{
    CustomForm,
    SimpleForm
};
use DaPigGuy\PiggyCustomEnchants\CustomEnchants\CustomEnchants;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use GreenJajot\CustomCraftUltimate\PiggyCustomEnchantsLoader;
use pocketmine\Player;
use pocketmine\nbt\tag\{CompoundTag, IntTag, StringTag, IntArrayTag};
use muqsit\invmenu\{InvMenu,InvMenuHandler};
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\utils\Config;
use pocketmine\inventory;
use pocketmine\math\Vector3;
use pocketmine\entity\Entity;
use pocketmine\block\Block;
use muqsit\invmenu\inventories\BaseFakeInventory;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerTransferEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\ProtectionEnchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\utils\TextFormat as f;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use DaPigGuy\PiggyCustomEnchants\Main as Custome;
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;

class Main extends PluginBase implements Listener{
	
	public const ITEM_FORMAT = [
        "id" => 1,
        "damage" => 0,
        "count" => 1,
        "display_name" => "",
        "lore" => [

        ],
        "enchants" => [

        ],
    ];
	public $recipe;
	public $customenchant;
	public $prefix = "§bCustomCraftUltimate ";

	public function onEnable() {
	$this->customenchant = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getLogger()->info($this->prefix . "Make By GreenJajot");
        if(!file_exists($this->getDataFolder() . "recipe.yml")) {
				@mkdir($this->getDataFolder());
				file_put_contents($this->getDataFolder() . "recipe.yml", $this->getResource("recipe.yml"));
        }
        @mkdir($this->getDataFolder());
        $this->recipe = new Config($this->getDataFolder() . "recipe.yml", Config::YAML);
        $this->saveDefaultConfig();
		if (!InvMenuHandler::isRegistered()) {
			InvMenuHandler::register($this);
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch($command->getName()){
			case "craftultimate":
					$this->menu($sender);
		    break;
		    case "listrecipeultimate":
		    $this->listform($sender);
		    break;
		    case "delrecipe":
		        if($sender->hasPermission("customcraft.edit")) {
		        if(!isset($args[0])){
		            $sender->sendMessage("Thường Dùng: /delrecipe <id> ex: 0, 1, ...");
               return false;
		        }
		        if(!is_numeric($args[0])){
               $sender->sendMessage("Thường Dùng: /delrecipe <id> ex: 0, 1, ...");
               return false;
           }
    $craf1 = $this->recipe->getNested('custom');
    $int = (int) $args[0];
    //$all = $this->recipe->getAll();
    foreach($craf1 as $a=>$b){
        if($a !== $int){
    $all['custom'][$a] = $b;
        }else{
    $sender->sendMessage("§l§e♦§b Custom §cCrafting §e♦ §f>> Xóa Thành Công Công Thức Id ".$int);
        }
    }
    $this->recipe->setAll($all);
$this->recipe->save();
$this->recipe->load($this->getDataFolder() . "recipe.yml", Config::YAML);
$this->recipe = new Config($this->getDataFolder() . "recipe.yml", Config::YAML);
           }
		break;
		    case "addrecipe":
		        if($sender->hasPermission("customcraft.edit")) {
		        if(!isset($args[0])){
               $sender->sendMessage("Thường Dùng: /addrecipe <name>");
               return false;
           }else{
    $this->addrecipe($sender, $args[0]);
           }
		        }
		    break;
		}
		return true;
	}
	
	public function addrecipe(Player $player, $name){
		
		$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$menu->setName("§l§e♦§b Custom §cCrafting §e♦");
		$menu->readonly(false);
		$minv = $menu->getInventory();
		$ai = Item::get(Item::STAINED_GLASS_PANE, 8)->setCustomName(" ");
		$air = Item::get(Item::AIR);
		$ai->setNamedTagEntry(new StringTag("craft", "none"));
		$unlimited = Item::get(Item::PAPER)->setCustomName($name);
		$green = Item::get(Item::EMERALD_BLOCK)->setCustomName("§l§a♦ §fChế Tạo §a♦");
		$unlimited->setNamedTagEntry(new StringTag("craft", "none"));
		$green->setNamedTagEntry(new StringTag("craft", "recipeyes"));
		$red = Item::get(Item::REDSTONE_BLOCK)->setCustomName("§l§d♦ §cDừng §d♦");
		$red->setNamedTagEntry(new StringTag("craft", "recipeno"));
		//$d = Item::get(264)->setCustomName("§bDiamond Kit");
		//$g = Item::get(266)->setCustomName("§6Gold Kit");
		//$b = Item::get(7)->setCustomName("§8Bedrock Kit");
		$minv->setItem(0, $air);
		$minv->setItem(1, $air);
		$minv->setItem(2, $air);
		$minv->setItem(3, $air);
		$minv->setItem(4, $air);
		$minv->setItem(5, $air);
		$minv->setItem(6, $ai);
		$minv->setItem(7, $ai);
		$minv->setItem(8, $ai);
		$minv->setItem(9, $air);
		$minv->setItem(10, $air);
		$minv->setItem(11, $air);
		$minv->setItem(12, $air);
		$minv->setItem(13, $air);
		$minv->setItem(14, $air);
		$minv->setItem(15, $ai);
		$minv->setItem(16, $ai);
		$minv->setItem(17, $ai);
		$minv->setItem(18, $air);
		$minv->setItem(19, $air);
		$minv->setItem(20, $air);
		$minv->setItem(21, $air);
		$minv->setItem(22, $air);
		$minv->setItem(23, $air);
		$minv->setItem(24, $ai);
		$minv->setItem(25, $air);
		$minv->setItem(26, $ai);
		$minv->setItem(27, $air);
		$minv->setItem(28, $air);
		$minv->setItem(29, $air);
		$minv->setItem(30, $air);
		$minv->setItem(31, $air);
		$minv->setItem(32, $air);
		$minv->setItem(33, $ai);
		$minv->setItem(34, $ai);
		$minv->setItem(35, $ai);
		$minv->setItem(36, $air);
		$minv->setItem(37, $air);
		$minv->setItem(38, $air);
		$minv->setItem(39, $air);
		$minv->setItem(40, $air);
		$minv->setItem(41, $air);
		$minv->setItem(42, $ai);
		$minv->setItem(43, $ai);
		$minv->setItem(44, $ai);
		$minv->setItem(45, $air);
		$minv->setItem(46, $air);
		$minv->setItem(47, $air);
		$minv->setItem(48, $air);
		$minv->setItem(49, $air);
		$minv->setItem(50, $air);
        $minv->setItem(51, $unlimited);
		$minv->setItem(52, $green);
		$minv->setItem(53, $red);
		$menu->send($player);
	}
	
	public function menu(Player $player) {
		
		$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$menu->setName("§l§e♦§b Custom §cCrafting §e♦");
		$menu->readonly(false);
		$minv = $menu->getInventory();
		$ai = Item::get(Item::STAINED_GLASS_PANE, 8)->setCustomName(" ");
		$air = Item::get(Item::AIR);
		$ai->setNamedTagEntry(new StringTag("craft", "none"));
		$unlimited = Item::get(397, 5)->setCustomName("§l§c♦ §fChế Tạo Không Giới Hạn (OP) §c♦");
		$green = Item::get(35, 13)->setCustomName("§l§a♦ §fChế Tạo §a♦");
		$unlimited->setNamedTagEntry(new StringTag("craft", "unlimited"));
		$green->setNamedTagEntry(new StringTag("craft", "yes"));
		$red = Item::get(426)->setCustomName("§l§f♦ §eXem Trước §f♦");
		$red->setNamedTagEntry(new StringTag("craft", "see"));
		//$d = Item::get(264)->setCustomName("§bDiamond Kit");
		//$g = Item::get(266)->setCustomName("§6Gold Kit");
		//$b = Item::get(7)->setCustomName("§8Bedrock Kit");
		$minv->setItem(0, $air);
		$minv->setItem(1, $air);
		$minv->setItem(2, $air);
		$minv->setItem(3, $air);
		$minv->setItem(4, $air);
		$minv->setItem(5, $air);
		$minv->setItem(6, $ai);
		$minv->setItem(7, $ai);
		$minv->setItem(8, $ai);
		$minv->setItem(9, $air);
		$minv->setItem(10, $air);
		$minv->setItem(11, $air);
		$minv->setItem(12, $air);
		$minv->setItem(13, $air);
		$minv->setItem(14, $air);
		$minv->setItem(15, $ai);
		$minv->setItem(16, $ai);
		$minv->setItem(17, $ai);
		$minv->setItem(18, $air);
		$minv->setItem(19, $air);
		$minv->setItem(20, $air);
		$minv->setItem(21, $air);
		$minv->setItem(22, $air);
		$minv->setItem(23, $air);
		$minv->setItem(24, $ai);
		$minv->setItem(25, $air);
		$minv->setItem(26, $ai);
		$minv->setItem(27, $air);
		$minv->setItem(28, $air);
		$minv->setItem(29, $air);
		$minv->setItem(30, $air);
		$minv->setItem(31, $air);
		$minv->setItem(32, $air);
		$minv->setItem(33, $ai);
		$minv->setItem(34, $ai);
		$minv->setItem(35, $ai);
		$minv->setItem(36, $air);
		$minv->setItem(37, $air);
		$minv->setItem(38, $air);
		$minv->setItem(39, $air);
		$minv->setItem(40, $air);
		$minv->setItem(41, $air);
		$minv->setItem(42, $ai);
		$minv->setItem(43, $ai);
		$minv->setItem(44, $ai);
		$minv->setItem(45, $air);
		$minv->setItem(46, $air);
		$minv->setItem(47, $air);
		$minv->setItem(48, $air);
		$minv->setItem(49, $air);
		$minv->setItem(50, $air);
		if($player->hasPermission("customcraft.unlimited")){
		$minv->setItem(51, $unlimited);
		}else{
        $minv->setItem(51, $ai);
		}
		$minv->setItem(52, $green);
		$minv->setItem(53, $red);
		$menu->send($player);
	}
	
	public function recipemenu(Player $player,$craft){
		
		$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$menu->setName("§l§e♦§b Custom §cCrafting §e♦");
		if($player->isOp()){
		$menu->readonly(false);
		}else{
		$menu->readonly(true);
		}
		$minv = $menu->getInventory();
		$ai = Item::get(Item::STAINED_GLASS_PANE, 8)->setCustomName(" ");
		$air = Item::get(Item::AIR);
		$ai->setNamedTagEntry(new StringTag("craft", "none"));
		$green = Item::get(35, 13)->setCustomName("§l§a♦ §fChế Tạo §a♦");
		$green->setNamedTagEntry(new StringTag("craft", "yes"));
		$red = Item::get(426)->setCustomName("§l§f♦ §eXem Trước §f♦");
		$red->setNamedTagEntry(new StringTag("craft", "see"));
		//$d = Item::get(264)->setCustomName("§bDiamond Kit");
		//$g = Item::get(266)->setCustomName("§6Gold Kit");
		//$b = Item::get(7)->setCustomName("§8Bedrock Kit");
		$minv->setItem(0, $this->dataToItem($craft['a1']));
		$minv->setItem(1, $this->dataToItem($craft['a2']));
		$minv->setItem(2, $this->dataToItem($craft['a3']));
		$minv->setItem(3, $this->dataToItem($craft['a4']));
		$minv->setItem(4, $this->dataToItem($craft['a5']));
		$minv->setItem(5, $this->dataToItem($craft['a6']));
		$minv->setItem(6, $ai);
		$minv->setItem(7, $ai);
		$minv->setItem(8, $ai);
		$minv->setItem(9, $this->dataToItem($craft['b1']));
		$minv->setItem(10, $this->dataToItem($craft['b2']));
		$minv->setItem(11, $this->dataToItem($craft['b3']));
		$minv->setItem(12, $this->dataToItem($craft['b4']));
		$minv->setItem(13, $this->dataToItem($craft['b5']));
		$minv->setItem(14, $this->dataToItem($craft['b6']));
		$minv->setItem(15, $ai);
		$minv->setItem(16, $ai);
		$minv->setItem(17, $ai);
		$minv->setItem(18, $this->dataToItem($craft['c1']));
		$minv->setItem(19, $this->dataToItem($craft['c2']));
		$minv->setItem(20, $this->dataToItem($craft['c3']));
		$minv->setItem(21, $this->dataToItem($craft['c4']));
		$minv->setItem(22, $this->dataToItem($craft['c5']));
		$minv->setItem(23, $this->dataToItem($craft['c6']));
		$minv->setItem(24, $ai);
		$minv->setItem(25, $this->dataToItem($craft['result']));
		$minv->setItem(26, $ai);
		$minv->setItem(27, $this->dataToItem($craft['d1']));
		$minv->setItem(28, $this->dataToItem($craft['d2']));
		$minv->setItem(29, $this->dataToItem($craft['d3']));
		$minv->setItem(30, $this->dataToItem($craft['d4']));
		$minv->setItem(31, $this->dataToItem($craft['d5']));
		$minv->setItem(32, $this->dataToItem($craft['d6']));
		$minv->setItem(33, $ai);
		$minv->setItem(34, $ai);
		$minv->setItem(35, $ai);
		$minv->setItem(36, $this->dataToItem($craft['e1']));
		$minv->setItem(37, $this->dataToItem($craft['e2']));
		$minv->setItem(38, $this->dataToItem($craft['e3']));
		$minv->setItem(39, $this->dataToItem($craft['e4']));
		$minv->setItem(40, $this->dataToItem($craft['e5']));
		$minv->setItem(41, $this->dataToItem($craft['e6']));
		$minv->setItem(42, $ai);
		$minv->setItem(43, $ai);
		$minv->setItem(44, $ai);
		$minv->setItem(45, $this->dataToItem($craft['f1']));
		$minv->setItem(46, $this->dataToItem($craft['f2']));
		$minv->setItem(47, $this->dataToItem($craft['f3']));
		$minv->setItem(48, $this->dataToItem($craft['f4']));
		$minv->setItem(49, $this->dataToItem($craft['f5']));
		$minv->setItem(50, $this->dataToItem($craft['f6']));
		$minv->setItem(51, $ai);
		$minv->setItem(52, $ai);
		$minv->setItem(53, $ai);
		$menu->send($player);
	}
	
	public function onTransaction(InventoryTransactionEvent $event){
		$transactions = $event->getTransaction()->getActions();
		$player = null;
		$chestinv = null;
		$action = null;
		foreach($transactions as $transaction){
			if($transaction instanceof SlotChangeAction) {
      if(($inv = $transaction->getInventory()) instanceof BaseFakeInventory){
					foreach($inv->getViewers() as $assumed){
						if($assumed instanceof Player){
							$player = $assumed;
							$this->chestinv = $chestinv = $inv;
							$action = $transaction;
							if(($player ?? $chestinv ?? $action) === null){
								return;
							}
								$item = $action->getSourceItem();
		
								/*if($item->getId() === Item::AIR){
									$this->writeLog($player->getName() . " Try to click Air Item in Auction House");
									return;
								}*/
								if($item->getNamedTag()->hasTag("craft")){
									$event->setCancelled(true);
									$menu = $item->getNamedTag()->getString("craft");
if($menu == "yes"){
		$this->dieukiencraft($chestinv);
}
if($menu == "see"){
		$this->dieukiensee($chestinv);	    
								}
if($menu == "unlimited"){
        $this->dieukienunlimited($chestinv);
                    }
if($menu == "recipeyes"){
        $this->newrecipe($chestinv, $player);
					}
if($menu == "recipeno"){
		$player->removeAllWindows();
                    }
					}
                }
            }
		}
	}
	}
	}
	
	public function dataToItem($itemData) : Item {
        $item = ItemFactory::get($itemData["id"] ?? 0, $itemData["damage"] ?? 0, $itemData["count"] ?? 1);
        if(isset($itemData["keytype"]))
        $item->setNamedTagEntry(new StringTag("KeyType", $itemData["keytype"]));
        if(isset($itemData["display_name"])) $item->setCustomName(TextFormat::colorize($itemData["display_name"]));
        if(isset($itemData["lore"])) {
            $lore = [];
            foreach($itemData["lore"] as $key=> $ilore){
                $lore[$key] = TextFormat::colorize($ilore);
            }
            $item->setLore($lore);
        }
        if(isset($itemData["enchants"])){
            foreach($itemData["enchants"] as $ename => $level){
                $ench = Enchantment::getEnchantment((int)$ename);
                if($ench === null){
                    $ce = $this->plugin->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
				if(!is_null($ce) && !is_null($enchant = CustomEnchants::getEnchantment((int)$ename))){
					if($ce instanceof Custome){
						$item = $ce->addEnchantment($item, $ename, $level);
					}
				}}else{
                    $item->addEnchantment(new EnchantmentInstance($ench, $level));
                }
            }
        }

        return $item;
    }
    
    public function dataToItem2($itemData) : Item {
        $item = ItemFactory::get($itemData["id"] ?? 0, $itemData["damage"] ?? 0, $itemData["count"] ?? 1);
        $item->setNamedTagEntry(new StringTag("craft", "none"));
        if(isset($itemData["keytype"]))
        $item->setNamedTagEntry(new StringTag("KeyType", $itemData["keytype"]));
        if(isset($itemData["display_name"])) $item->setCustomName(TextFormat::colorize($itemData["display_name"]));
        if(isset($itemData["lore"])) {
            $lore = [];
            foreach($itemData["lore"] as $key=> $ilore){
                $lore[$key] = TextFormat::colorize($ilore);
            }
            $item->setLore($lore);
        }
        if(isset($itemData["enchants"])){
            foreach($itemData["enchants"] as $ename => $level){
                $ench = Enchantment::getEnchantment((int)$ename);
                if($ench === null){
                    $ce = $this->plugin->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
				if(!is_null($ce) && !is_null($enchant = CustomEnchants::getEnchantment((int)$ename))){
					if($ce instanceof Custome){
						$item = $ce->addEnchantment($item, $ename, $level);
					}
				}}else{
                    $item->addEnchantment(new EnchantmentInstance($ench, $level));
                }
            }
        }

        return $item;
    }
	
	public function onDisable() : void{
		$this->getLogger()->info("Bye");
	}

    public function dieukiencraft($chestinv){
        $craf1 = $this->recipe->getNested('custom');
            foreach($craf1 as $craft1=>$craft2){
            $craf3 = array($craft1=>$craft2);
            foreach($craf3 as $craft){
	    if($this->itemToData($itemslot = $chestinv->getItem(0)) == $this->dataToItem($shape = $craft['a1']) or ($craft["a1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(1)) == $this->dataToItem($shape = $craft['a2']) or ($craft["a2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(2)) == $this->dataToItem($shape = $craft['a3']) or ($craft["a3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(3)) == $this->dataToItem($shape = $craft['a4']) or ($craft["a4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(4)) == $this->dataToItem($shape = $craft['a5']) or ($craft["a5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(5)) == $this->dataToItem($shape = $craft['a6']) or ($craft["a6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(9)) == $this->dataToItem($shape = $craft['b1']) or ($craft["b1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(10)) == $this->dataToItem($shape = $craft['b2']) or ($craft["b2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(11)) == $this->dataToItem($shape = $craft['b3']) or ($craft["b3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(12)) == $this->dataToItem($shape = $craft['b4']) or ($craft["b4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(13)) == $this->dataToItem($shape = $craft['b5']) or ($craft["b5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(14)) == $this->dataToItem($shape = $craft['b6']) or ($craft["b6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(18)) == $this->dataToItem($shape = $craft['c1']) or ($craft["c1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(19)) == $this->dataToItem($shape = $craft['c2']) or ($craft["c2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(20)) == $this->dataToItem($shape = $craft['c3']) or ($craft["c3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(21)) == $this->dataToItem($shape = $craft['c4']) or ($craft["c4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(22)) == $this->dataToItem($shape = $craft['c5']) or ($craft["c5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(23)) == $this->dataToItem($shape = $craft['c6']) or ($craft["c6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(27)) == $this->dataToItem($shape = $craft['d1']) or ($craft["d1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(28)) == $this->dataToItem($shape = $craft['d2']) or ($craft["d2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(29)) == $this->dataToItem($shape = $craft['d3']) or ($craft["d3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(30)) == $this->dataToItem($shape = $craft['d4']) or ($craft["d4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(31)) == $this->dataToItem($shape = $craft['d5']) or ($craft["d5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(32)) == $this->dataToItem($shape = $craft['d6']) or ($craft["d6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(36)) == $this->dataToItem($shape = $craft['e1']) or ($craft["e1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(37)) == $this->dataToItem($shape = $craft['e2']) or ($craft["e2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(38)) == $this->dataToItem($shape = $craft['e3']) or ($craft["e3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(39)) == $this->dataToItem($shape = $craft['e4']) or ($craft["e4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(40)) == $this->dataToItem($shape = $craft['e5']) or ($craft["e5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(41)) == $this->dataToItem($shape = $craft['e6']) or ($craft["e6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(45)) == $this->dataToItem($shape = $craft['f1']) or ($craft["f1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(46)) == $this->dataToItem($shape = $craft['f2']) or ($craft["f2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(47)) == $this->dataToItem($shape = $craft['f3']) or ($craft["f3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(48)) == $this->dataToItem($shape = $craft['f4']) or ($craft["f4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(49)) == $this->dataToItem($shape = $craft["f5"]) or ($craft["f5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(50)) == $this->dataToItem($shape = $craft['f6']) or ($craft["f6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
		    $matui = array(0, 1, 2, 3, 4, 5, 
9, 10, 11, 12, 13, 14, 
18, 19, 20, 21, 22, 23, 
27, 28, 29, 30, 31, 32, 
36, 37, 38, 39, 40, 41, 
45, 46, 47, 48, 49, 50);
foreach($matui as $matuis){
		    $chestinv->setItem($matuis, Item::get(Item::AIR));
}
		    $chestinv->setItem(25,$this->dataToItem($craft['result']));
	                            }
							}	}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}
	}}
	
	public function newrecipe($chestinv,Player $player){
        $craf1 = $this->recipe->getNested('custom');
        $count = count($craf1);
        $all = $this->recipe->getAll();
        $name = $chestinv->getItem(51)->getCustomName();
        $listslot = array(
"a1" => 0,
"a2" => 1,
"a3" => 2,
"a4" => 3,
"a5" => 4,
"a6" => 5,
"b1" => 9,
"b2" => 10,
"b3" => 11,
"b4" => 12,
"b5" => 13,
"b6" => 14,
"c1" => 18,
"c2" => 19,
"c3" => 20,
"c4" => 21,
"c5" => 22,
"c6" => 23,
"d1" => 27,
"d2" => 28,
"d3" => 29,
"d4" => 30,
"d5" => 31,
"d6" => 32,
"e1" => 36,
"e2" => 37,
"e3" => 38,
"e4" => 39,
"e5" => 40,
"e6" => 41,
"f1" => 45,
"f2" => 46,
"f3" => 47,
"f4" => 48,
"f5" => 49,
"f6" => 50,
"result" =>25
);
$all['custom'][$count]["name"] = $name;
foreach($listslot as $names=>$ids){
    $item = $chestinv->getItem($ids);
    if($item->getId() == Item::AIR){
    $all['custom'][$count][$names]['id'] = 0;
    }else{
    $all['custom'][$count][$names] = $this->itemToData1($item);
    }
}
$this->recipe->setAll($all);
$this->recipe->save();
$this->recipe->load($this->getDataFolder() . "recipe.yml", Config::YAML);
$this->recipe = new Config($this->getDataFolder() . "recipe.yml", Config::YAML);
$player->sendMessage("§l§e♦§b Custom §cCrafting §e♦ §f>> Tạo Thành Công Công Thức  ".$name);
$player->removeAllWindows();
}
    public function dieukienunlimited($chestinv){
        $craf1 = $this->recipe->getNested('custom');
            foreach($craf1 as $craft1=>$craft2){
            $craf3 = array($craft1=>$craft2);
            foreach($craf3 as $craft){
	    if($this->itemToData($itemslot = $chestinv->getItem(0)) == $this->dataToItem($shape = $craft['a1']) or ($craft["a1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(1)) == $this->dataToItem($shape = $craft['a2']) or ($craft["a2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(2)) == $this->dataToItem($shape = $craft['a3']) or ($craft["a3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(3)) == $this->dataToItem($shape = $craft['a4']) or ($craft["a4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(4)) == $this->dataToItem($shape = $craft['a5']) or ($craft["a5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(5)) == $this->dataToItem($shape = $craft['a6']) or ($craft["a6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(9)) == $this->dataToItem($shape = $craft['b1']) or ($craft["b1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(10)) == $this->dataToItem($shape = $craft['b2']) or ($craft["b2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(11)) == $this->dataToItem($shape = $craft['b3']) or ($craft["b3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(12)) == $this->dataToItem($shape = $craft['b4']) or ($craft["b4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(13)) == $this->dataToItem($shape = $craft['b5']) or ($craft["b5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(14)) == $this->dataToItem($shape = $craft['b6']) or ($craft["b6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(18)) == $this->dataToItem($shape = $craft['c1']) or ($craft["c1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(19)) == $this->dataToItem($shape = $craft['c2']) or ($craft["c2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(20)) == $this->dataToItem($shape = $craft['c3']) or ($craft["c3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(21)) == $this->dataToItem($shape = $craft['c4']) or ($craft["c4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(22)) == $this->dataToItem($shape = $craft['c5']) or ($craft["c5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(23)) == $this->dataToItem($shape = $craft['c6']) or ($craft["c6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(27)) == $this->dataToItem($shape = $craft['d1']) or ($craft["d1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(28)) == $this->dataToItem($shape = $craft['d2']) or ($craft["d2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(29)) == $this->dataToItem($shape = $craft['d3']) or ($craft["d3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(30)) == $this->dataToItem($shape = $craft['d4']) or ($craft["d4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(31)) == $this->dataToItem($shape = $craft['d5']) or ($craft["d5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(32)) == $this->dataToItem($shape = $craft['d6']) or ($craft["d6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(36)) == $this->dataToItem($shape = $craft['e1']) or ($craft["e1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(37)) == $this->dataToItem($shape = $craft['e2']) or ($craft["e2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(38)) == $this->dataToItem($shape = $craft['e3']) or ($craft["e3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(39)) == $this->dataToItem($shape = $craft['e4']) or ($craft["e4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(40)) == $this->dataToItem($shape = $craft['e5']) or ($craft["e5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(41)) == $this->dataToItem($shape = $craft['e6']) or ($craft["e6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(45)) == $this->dataToItem($shape = $craft['f1']) or ($craft["f1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(46)) == $this->dataToItem($shape = $craft['f2']) or ($craft["f2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(47)) == $this->dataToItem($shape = $craft['f3']) or ($craft["f3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(48)) == $this->dataToItem($shape = $craft['f4']) or ($craft["f4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(49)) == $this->dataToItem($shape = $craft["f5"]) or ($craft["f5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(50)) == $this->dataToItem($shape = $craft['f6']) or ($craft["f6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
		    $chestinv->setItem(25,$this->dataToItem($craft['result']));
	                            }
							}	}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}
	}}
	
	public function dieukiensee($chestinv){
        $craf1 = $this->recipe->getNested('custom');
        foreach($craf1 as $craft1=>$craft2){
            $craf3 = array($craft1=>$craft2);
            foreach($craf3 as $craft){
	    if($this->itemToData($itemslot = $chestinv->getItem(0)) == $this->dataToItem($shape = $craft['a1']) or ($craft["a1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(1)) == $this->dataToItem($shape = $craft['a2']) or ($craft["a2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(2)) == $this->dataToItem($shape = $craft['a3']) or ($craft["a3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(3)) == $this->dataToItem($shape = $craft['a4']) or ($craft["a4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(4)) == $this->dataToItem($shape = $craft['a5']) or ($craft["a5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(5)) == $this->dataToItem($shape = $craft['a6']) or ($craft["a6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(9)) == $this->dataToItem($shape = $craft['b1']) or ($craft["b1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(10)) == $this->dataToItem($shape = $craft['b2']) or ($craft["b2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(11)) == $this->dataToItem($shape = $craft['b3']) or ($craft["b3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(12)) == $this->dataToItem($shape = $craft['b4']) or ($craft["b4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(13)) == $this->dataToItem($shape = $craft['b5']) or ($craft["b5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(14)) == $this->dataToItem($shape = $craft['b6']) or ($craft["b6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(18)) == $this->dataToItem($shape = $craft['c1']) or ($craft["c1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(19)) == $this->dataToItem($shape = $craft['c2']) or ($craft["c2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(20)) == $this->dataToItem($shape = $craft['c3']) or ($craft["c3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(21)) == $this->dataToItem($shape = $craft['c4']) or ($craft["c4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(22)) == $this->dataToItem($shape = $craft['c5']) or ($craft["c5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(23)) == $this->dataToItem($shape = $craft['c6']) or ($craft["c6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(27)) == $this->dataToItem($shape = $craft['d1']) or ($craft["d1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(28)) == $this->dataToItem($shape = $craft['d2']) or ($craft["d2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(29)) == $this->dataToItem($shape = $craft['d3']) or ($craft["d3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(30)) == $this->dataToItem($shape = $craft['d4']) or ($craft["d4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(31)) == $this->dataToItem($shape = $craft['d5']) or ($craft["d5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(32)) == $this->dataToItem($shape = $craft['d6']) or ($craft["d6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(36)) == $this->dataToItem($shape = $craft['e1']) or ($craft["e1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(37)) == $this->dataToItem($shape = $craft['e2']) or ($craft["e2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(38)) == $this->dataToItem($shape = $craft['e3']) or ($craft["e3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(39)) == $this->dataToItem($shape = $craft['e4']) or ($craft["e4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(40)) == $this->dataToItem($shape = $craft['e5']) or ($craft["e5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(41)) == $this->dataToItem($shape = $craft['e6']) or ($craft["e6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(45)) == $this->dataToItem($shape = $craft['f1']) or ($craft["f1"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(46)) == $this->dataToItem($shape = $craft['f2']) or ($craft["f2"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(47)) == $this->dataToItem($shape = $craft['f3']) or ($craft["f3"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(48)) == $this->dataToItem($shape = $craft['f4']) or ($craft["f4"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(49)) == $this->dataToItem($shape = $craft["f5"]) or ($craft["f5"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	    if($this->itemToData($itemslot = $chestinv->getItem(50)) == $this->dataToItem($shape = $craft['f6']) or ($craft["f6"]["id"] == 0 and $itemslot->getId() === Item::AIR)){
	        $item1 = $this->dataToItem2($craft['result']);
		    $chestinv->setItem(25,$item1);
	                            }
							}	}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}
	}}
	
	public function itemToData1(Item $item) : array {
        $itemData = self::ITEM_FORMAT;
        $itemData["id"] = $item->getId();
        $itemData["damage"] = $item->getDamage();
        $itemData["count"] = $item->getCount();
        if($item->hasCustomName()){
            $itemData["display_name"] = $item->getCustomName();
        }else{
            unset($itemData["display_name"]);
        }
        if($item->getLore() !== []){
            $itemData["lore"] = $item->getLore();
        }else{
            unset($itemData["lore"]);
        }
        if($item->hasEnchantments()){
            foreach($item->getEnchantments() as $enchantment){
                $itemData["enchants"][(string)$enchantment->getId()] = $enchantment->getLevel();
            }
        }else{
            unset($itemData["enchants"]);
        }

        return $itemData;
    }
	public function itemToData(Item $item) : Item {
        $itemData["id"] = $item->getId();
        $itemData["damage"] = $item->getDamage();
        $itemData["count"] = $item->getCount();
        if($item->hasCustomName()){
            $itemData["display_name"] = $item->getCustomName();
        }
        if($item->getLore() !== []){
            $itemData["lore"] = $item->getLore();
        }
        if($item->hasEnchantments()){
            foreach($item->getEnchantments() as $enchantment){
                $itemData["enchants"][(string)$enchantment->getId()] = $enchantment->getLevel();
            }
        }
    $item = ItemFactory::get($itemData["id"] ?? 0, $itemData["damage"] ?? 0, $itemData["count"] ?? 1);
        if(isset($itemData["keytype"]))
        $item->setNamedTagEntry(new StringTag("KeyType", $itemData["keytype"]));
        if(isset($itemData["display_name"])) $item->setCustomName(TextFormat::colorize($itemData["display_name"]));
        if(isset($itemData["lore"])) {
            $lore = [];
            foreach($itemData["lore"] as $key=> $ilore){
                $lore[$key] = TextFormat::colorize($ilore);
            }
            $item->setLore($lore);
        }
        if(isset($itemData["enchants"])){
            foreach($itemData["enchants"] as $ename => $level){
                $ench = Enchantment::getEnchantment((int)$ename);
                if($ench === null){
                    $ce = $this->plugin->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
				if(!is_null($ce) && !is_null($enchant = CustomEnchants::getEnchantment((int)$ename))){
					if($ce instanceof Custome){
						$item = $ce->addEnchantment($item, $ename, $level);
					}
				}}else{
                    $item->addEnchantment(new EnchantmentInstance($ench, $level));
                }
            }
        }

        return $item;
    }
    
    public function listform(Player $player): void{
        $form = new SimpleForm(function (Player $player, $data = null){
            if ($data === null){
                return;
            }
            $this->buyForm($player, $data);
        });
        foreach($this->recipe->getNested('custom') as $name){
                $var = array('name' => $name['name']);
            
			$form->addButton("§e→ §fCông Thức: " . $var['name']);
        }
		
        $form->setTitle("§l§e♦§b Custom §cCrafting §e♦");
        $player->sendForm($form);
    }
    
	/**
    * @param Player $player
    * @param int $id
    */
    public function buyForm(Player $player,int $id){
        $craf1 = $this->recipe->getNested('custom');
            $craft = $craf1[$id];
    $this->recipemenu($player,$craft);
    }
}