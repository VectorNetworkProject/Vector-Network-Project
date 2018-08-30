<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/27
 * Time: 16:43
 */

namespace Core\Task;


use Core\Main;

class AutoSavingTask extends PluginTask
{
	public function __construct(Main $plugin)
	{
		parent::__construct($plugin);
	}
	public function onRun(int $currentTick)
	{
		$this->owner->getServer()->getLevelByName("ffapvp")->save(true);
		$this->owner->getServer()->getLevelByName("lobby")->save(true);
		$this->owner->getServer()->getLevelByName("Survival")->save(true);
		$this->owner->getServer()->getLevelByName("duel")->save(true);
	}
}