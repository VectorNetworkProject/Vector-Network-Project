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

class EntityShootBow
{
	protected $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function event(EntityShootBowEvent $event)
	{
	}
}