<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 15:44
 */

namespace Core\Event;

use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Main;
use pocketmine\event\block\BlockBreakEvent;

class BlockBreak
{
	protected $plugin, $speedcorepvp;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->speedcorepvp = new SpeedCorePvPCore($this->plugin);
	}

	public function event(BlockBreakEvent $event)
	{
		/*
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $event->setCancelled(true);
        }
        */
		$this->speedcorepvp->DropItem($event);
		$this->speedcorepvp->BreakCore($event);
	}
}
