<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 15:44
 */

namespace Core\Event;

use Core\Game\Survival\SurvivalCore;
use Core\Main;
use pocketmine\event\block\BlockBreakEvent;

class BlockBreak
{
	protected $plugin, $survival;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->survival = new SurvivalCore($this->plugin);
	}

	public function event(BlockBreakEvent $event)
	{
		$this->survival->AddBreakCount($event->getPlayer());
	}
}
