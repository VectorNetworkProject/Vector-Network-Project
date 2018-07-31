<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/31
 * Time: 14:28
 */

namespace Core\Task;


use pocketmine\entity\object\ItemEntity;
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
			if ($entity instanceof ItemEntity) {
				$entity->kill();
			}
		}
	}
}