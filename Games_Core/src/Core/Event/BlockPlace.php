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
use pocketmine\event\Listener;

class BlockPlace implements Listener
{
	/** @var Main */
	private $plugin;
	/** @var SurvivalCore */
	private $survival;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		// TODO: Rewrite
		$this->survival = new SurvivalCore($this->plugin);
	}

	public function event(BlockPlaceEvent $event): void
	{
		$this->survival->AddPlaceCount($event->getPlayer());
	}
}
