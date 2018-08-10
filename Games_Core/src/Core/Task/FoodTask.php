<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/06
 * Time: 11:50
 */

namespace Core\Task;


use pocketmine\plugin\Plugin;

class FoodTask extends PluginTask
{
	public function __construct(Plugin $plugin)
	{
		parent::__construct($plugin);
	}

	public function onRun(int $currentTick)
	{
		foreach ($this->owner->getServer()->getOnlinePlayers() as $player) {
			if ($player->getLevel()->getName() === "lobby") {
				$player->setFood(20);
			}
		}
	}
}