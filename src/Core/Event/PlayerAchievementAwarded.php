<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/09/04
 * Time: 11:41
 */

namespace Core\Event;


use Core\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerAchievementAwardedEvent;

class PlayerAchievementAwarded implements Listener
{
	public function __construct(Main $plugin)
	{

	}
	public function event(PlayerAchievementAwardedEvent $event)
	{
		$event->setCancelled();
	}
}