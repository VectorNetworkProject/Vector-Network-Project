<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/22
 * Time: 13:59
 */

namespace Core\Event;

use Core\Game\FFAPvP\FFAPvPCore;
use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Main;
use pocketmine\block\Block;
use pocketmine\event\player\PlayerInteractEvent;

class PlayerInteract
{
	protected $plugin, $ffapvp, $speedcorepvp;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->ffapvp = new FFAPvPCore($this->plugin);
		$this->speedcorepvp = new SpeedCorePvPCore($this->plugin);
	}

	public function event(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$this->ffapvp->FFAPvPKit($player, $block);
		$this->speedcorepvp->GameJoin($player, $block);
	}
}
