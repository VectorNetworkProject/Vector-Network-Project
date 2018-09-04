<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/09/04
 * Time: 11:38
 */

namespace Core\Event;


use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Game\Survival\SurvivalCore;
use Core\Main;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;

class SignChange implements Listener
{
	private $survival, $speedcorepvp;
	public function __construct(Main $plugin)
	{
		$this->speedcorepvp = new SpeedCorePvPCore($plugin);
		$this->survival = new SurvivalCore($plugin);
	}

	public function event(SignChangeEvent $event)
	{
		$this->speedcorepvp->Sign($event);
		$this->survival->Sign($event);
	}
}