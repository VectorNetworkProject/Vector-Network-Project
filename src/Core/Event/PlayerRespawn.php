<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/28
 * Time: 20:51
 */

namespace Core\Event;

use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;

class PlayerRespawn implements Listener
{
	private $speedcorepvp;

	public function __construct(Main $plugin)
	{
		$this->speedcorepvp = new SpeedCorePvPCore($plugin);
	}

	public function event(PlayerRespawnEvent $event): void
	{
		$this->speedcorepvp->Respawn($event->getPlayer());
	}
}