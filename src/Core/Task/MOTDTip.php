<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/18
 * Time: 16:05
 */

namespace Core\Task;


use pocketmine\plugin\Plugin;

class MOTDTip extends PluginTask
{
	public function __construct(Plugin $plugin)
	{
		parent::__construct($plugin);
	}

	public function onRun(int $currentTick)
	{
		$rand = mt_rand(1, 5);
		switch ($rand) {
			case 1:
				$this->owner->getServer()->getNetwork()->setName("§l§6>§e>§6> §a3GAMES §6<§e<§6<§7");
				break;
			case 2:
				$this->owner->getServer()->getNetwork()->setName("§l§6>§e>§6> §aSURVIVAL §6<§e<§6<§7");
				break;
			case 3:
				$this->owner->getServer()->getNetwork()->setName("§l§6>§e>§6> §bSpeed§aCore§cPvP §6<§e<§6<§7");
				break;
			case 4:
				$this->owner->getServer()->getNetwork()->setName("§l§6>§e>§6> §6FFA§cPvP §6<§e<§6<§7");
				break;
			default:
				$this->owner->getServer()->getNetwork()->setName("§l§6Vector §bNetwork §eProject§7");
				break;
		}
	}
}