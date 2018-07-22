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
                $armor = $player->getArmorInventory();
                $armor->setHelmet(Item::get(Item::LEATHER_CAP, 0, 1));
                $armor->setChestplate(Item::get(Item::LEATHER_TUNIC, 0, 1));
                $armor->setLeggings(Item::get(Item::LEATHER_PANTS, 0, 1));
                $armor->setBoots(Item::get(Item::LEATHER_BOOTS, 0, 1));
                $item = Item::get(Item::WOODEN_SWORD, 0, 1);
                $player->getInventory()->addItem($item);
                $player->getInventory()->addItem(Item::get(Item::STEAK, 0, 64));
                $player->sendMessage("§a初期装備を与えました。テスト公開ですがお楽しみください");
            }
        }
    }
}
