<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/09/04
 * Time: 11:42
 */

namespace Core\Event;


use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class PlayerChat implements Listener
{
	private $speedcorepvp;
	public function __construct(Main $plugin)
	{
		$this->speedcorepvp = new SpeedCorePvPCore($plugin);
	}

	public function event(PlayerChatEvent $event)
	{
		$this->speedcorepvp->TeamChat($event);
	}
}