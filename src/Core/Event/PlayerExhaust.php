<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/10
 * Time: 17:29
 */

namespace Core\Event;

use Core\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;

class PlayerExhaust implements Listener
{
	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function event(PlayerExhaustEvent $event): void
	{
		if ($event->getPlayer()->getLevel()->getName() === 'lobby') {
			$event->setCancelled(true);
		}
	}
}