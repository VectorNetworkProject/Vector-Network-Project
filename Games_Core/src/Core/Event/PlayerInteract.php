<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/22
 * Time: 13:59
 */

namespace Core\Event;

use Core\Game\FFAPvP\FFAPvPCore;
use Core\Main;
use pocketmine\event\player\PlayerInteractEvent;

class PlayerInteract
{
	protected $plugin, $ffapvp;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->ffapvp = new FFAPvPCore($this->plugin);
	}

	public function event(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$this->ffapvp->FFAPvPKit($player, $block);
	}
}
