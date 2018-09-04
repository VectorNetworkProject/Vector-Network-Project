<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/09/04
 * Time: 11:34
 */

namespace Core\Event;


use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Main;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\Listener;

class CraftItem implements Listener
{
	private $speedcorepvp;
	public function __construct(Main $plugin)
	{
		$this->speedcorepvp = new SpeedCorePvPCore($plugin);
	}
	public function event(CraftItemEvent $event)
	{
		$this->speedcorepvp->CancelCraft($event);
	}
}