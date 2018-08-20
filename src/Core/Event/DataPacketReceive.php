<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 13:19
 */

namespace Core\Event;

use Core\Commands\MessagesEnum;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class DataPacketReceive implements Listener
{

	public function event(DataPacketReceiveEvent $event): void
	{
		$packet = $event->getPacket();
		$player = $event->getPlayer();
		if ($packet instanceof ModalFormResponsePacket) {
			if ($packet->formId === 489234852) {
				if (($data = json_decode($packet->formData)) === null) {
					return;
				}
				switch ($data) {
					case 0:
						if ($player->getInventory()->contains(Item::get(Item::GOLD_INGOT, 0, 12))) {
							$player->getInventory()->removeItem(Item::get(Item::GOLD_INGOT, 0, 12));
							$player->getInventory()->addItem(Item::get(Item::BOW, 0, 1));
							$player->sendMessage(MessagesEnum::BUY_SUCCESS);
						} else {
							$player->sendMessage("§7[§c失敗§7] §6金§cが足りません。");
						}
						break;
					case 1:
						if ($player->getInventory()->contains(Item::get(Item::GOLD_INGOT, 0, 2))) {
							$player->getInventory()->removeItem(Item::get(Item::GOLD_INGOT, 0, 2));
							$player->getInventory()->addItem(Item::get(Item::ARROW, 0, 6));
							$player->sendMessage(MessagesEnum::BUY_SUCCESS);
						} else {
							$player->sendMessage("§7[§c失敗§7] §6金§cが足りません。");
						}
						break;
					case 2:
						if ($player->getInventory()->contains(Item::get(Item::GOLD_INGOT, 0, 50))) {
							$player->getInventory()->removeItem(Item::get(Item::GOLD_INGOT, 0, 50));
							$player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 1));
							$player->sendMessage(MessagesEnum::BUY_SUCCESS);
						} else {
							$player->sendMessage("§7[§c失敗§7] §6金§cが足りません。");
						}
						break;
					case 3:
						if ($player->getInventory()->contains(Item::get(Item::GOLD_INGOT, 0, 100))) {
							$player->getInventory()->removeItem(Item::get(Item::GOLD_INGOT, 0, 100));
							$player->getInventory()->addItem(Item::get(Item::APPLE_ENCHANTED, 0, 1));
							$player->sendMessage(MessagesEnum::BUY_SUCCESS);
						} else {
							$player->sendMessage("§7[§c失敗§7] §6金§cが足りません。");
						}
						break;
				}
			}
		}
	}
}
