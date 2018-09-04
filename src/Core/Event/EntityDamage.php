<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 15:28
 */

namespace Core\Event;

use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Main;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class EntityDamage implements Listener
{
	private $speedcorepvp;

	public function __construct(Main $plugin)
	{
		$this->speedcorepvp = new SpeedCorePvPCore($plugin);
	}

	public function event(EntityDamageEvent $event)
	{
		$entity = $event->getEntity();
		$this->speedcorepvp->damage($event);
		if ($entity->getLevel()->getName() === "ffapvp" or $entity->getLevel()->getName() === "corepvp") {
			if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
				$event->setCancelled(true);
			}
		}
		if ($event instanceof EntityDamageByEntityEvent and $entity instanceof Player) {
			$damager = $event->getDamager();
			if ($damager instanceof Player) {
				if ($damager->getName() === $entity->getName()) {
					$event->setCancelled(true);
				}
			}
		}
	}
}
