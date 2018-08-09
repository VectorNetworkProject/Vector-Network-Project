<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 15:48
 */

namespace Core\Event;

use Core\Game\Survival\SurvivalCore;
use Core\Main;
use pocketmine\event\block\BlockPlaceEvent;

class BlockPlace
{
	protected $plugin, $survival;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->survival = new SurvivalCore($this->plugin);
	}

	public function event(BlockPlaceEvent $event)
	{
		$this->survival->AddPlaceCount($event->getPlayer());
	}
}
