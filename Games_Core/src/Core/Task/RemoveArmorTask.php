<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/31
 * Time: 14:25
 */

namespace Core\Task;


use pocketmine\Player;
use pocketmine\plugin\Plugin;

class RemoveArmorTask extends PluginTask
{
	protected $player;

	public function __construct(Plugin $plugin, Player $player)
	{
		parent::__construct($plugin);
		$this->player = $player;
	}

	public function onRun(int $currentTick)
	{
		$this->player->getArmorInventory()->clearAll(true);
	}
}