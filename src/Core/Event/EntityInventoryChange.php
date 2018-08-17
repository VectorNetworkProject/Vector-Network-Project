<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/30
 * Time: 8:17
 */

namespace Core\Event;

use Core\Main;
use pocketmine\event\entity\EntityInventoryChangeEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;

class EntityInventoryChange implements Listener
{
	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function event(EntityInventoryChangeEvent $event): void
	{
		if ($event->getEntity()->getLevel()->getName() === "corepvp") {
			if ($event->getSlot() === 0) {
				if ($event->getOldItem()->getId() === Item::LEATHER_HELMET) {
					$event->setCancelled(true);
				}
			}
		}
	}
}