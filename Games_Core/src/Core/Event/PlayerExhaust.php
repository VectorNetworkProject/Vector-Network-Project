<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/10
 * Time: 17:29
 */

namespace Core\Event;


use Core\Main;
use pocketmine\event\player\PlayerExhaustEvent;

class PlayerExhaust
{
	protected $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function event(PlayerExhaustEvent $event)
	{
		if ($event->getPlayer()->getLevel()->getName() === 'lobby') {
			$event->setCancelled(true);
		}
	}
}