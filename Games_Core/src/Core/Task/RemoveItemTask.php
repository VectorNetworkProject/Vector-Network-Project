<?php
/**
 * Created by PhpStorm.
 * User: PCink
 * Date: 2018/07/31
 * Time: 14:28
 */

namespace Core\Task;


use pocketmine\item\Item;
use pocketmine\plugin\Plugin;

class RemoveItemTask extends PluginTask
{
	public function __construct(Plugin $plugin)
	{
		parent::__construct($plugin);
	}

	public function onRun(int $currentTick)
	{
		foreach ($this->owner->getServer()->getLevelByName("corepvp")->getEntities() as $entity) {
			if ($entity instanceof Item) {
				$entity->kill();
			}
		}
	}
}