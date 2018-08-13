<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/30
 * Time: 10:06
 */

namespace Core\Event;


use Core\Main;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\Listener;

class EntityShootBow implements Listener
{
	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function event(EntityShootBowEvent $event): void
	{
		$event->setForce($event->getForce() + 0.5);
	}
}