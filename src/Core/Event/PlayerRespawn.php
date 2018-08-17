<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/28
 * Time: 20:51
 */

namespace Core\Event;

use Core\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerRespawnEvent;

class PlayerRespawn implements Listener
{
	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function event(PlayerRespawnEvent $event): void
	{
		// なにもしない
	}
}