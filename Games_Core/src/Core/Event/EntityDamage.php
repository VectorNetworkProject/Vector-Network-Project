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
use pocketmine\event\entity\EntityDamageEvent;

class EntityDamage
{
	protected $plugin, $speedcorepvp;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->speedcorepvp = new SpeedCorePvPCore($this->plugin);
	}

	public function event(EntityDamageEvent $event)
	{
		$this->speedcorepvp->Damage($event);
		if ($event->getEntity()->getLevel()->getName() === "ffapvp" or $event->getEntity()->getLevel()->getName() === "corepvp") {
			if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
				$event->setCancelled(true);
			}
		}
	}
}
