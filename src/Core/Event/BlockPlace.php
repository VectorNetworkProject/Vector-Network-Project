<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 15:48
 */

namespace Core\Event;

use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Game\Survival\SurvivalCore;
use Core\Main;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class BlockPlace implements Listener
{
	/** @var SpeedCorePvPCore */
	private $speedcorepvp;
	/** @var SurvivalCore */
	private $survival;

	public function __construct(Main $plugin)
	{
		// TODO: Rewrite
		$this->survival = new SurvivalCore($plugin);
		$this->speedcorepvp = new SpeedCorePvPCore($plugin);
	}

	public function event(BlockPlaceEvent $event): void
	{
		$this->survival->AddPlaceCount($event->getPlayer());
		$this->speedcorepvp->AntiPlace($event);
	}
}
