<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/22
 * Time: 13:59
 */
namespace Core\Event;

use Core\Main;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Durable;
use pocketmine\item\Item;

class PlayerInteract
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function event(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        if ($player->getLevel()->getName() === "ffapvp") {
            if ($event->getBlock()->getId() === 133) {
                $player->getInventory()->clearAll(true);
                $player->setMaxHealth(20);
                $player->setHealth(20);
                $player->setFood(20);
                $items = [
                    "leather_cap" => Item::get(Item::LEATHER_CAP, 0, 1),
                    "leather_tunic" => Item::get(Item::LEATHER_TUNIC, 0, 1),
                    "leather_pants" => Item::get(Item::LEATHER_PANTS, 0, 1),
                    "leather_boots" => Item::get(Item::LEATHER_BOOTS, 0, 1),
                    "wooden_sword" => Item::get(Item::WOODEN_SWORD, 0, 1)
                ];
                foreach ($items as $item) {
                    if ($item instanceof Durable) {
                        $item->setUnbreakable(true);
                    }
                }
                $armor = $player->getArmorInventory();
                $armor->setHelmet($items["leather_cap"]);
                $armor->setChestplate($items["leather_tunic"]);
                $armor->setLeggings($items["leather_pants"]);
                $armor->setBoots($items["leather_boots"]);
                $player->getInventory()->addItem($items["wooden_sword"]);
                $player->getInventory()->addItem(Item::get(Item::STEAK, 0, 64));
                $player->sendMessage("§a初期装備を与えました。テスト公開ですがお楽しみください");
            }
        }
    }
}
