<?php
/**
 * Created by PhpStorm.
 * User: PCink
 * Date: 2018/07/28
 * Time: 14:27
 */

namespace Core\Task\Teleport;


use Core\Main;
use Core\Task\PluginTask;
use pocketmine\level\Position;
use pocketmine\Player;

class TeleportSpeedCorePvPTask extends PluginTask
{
	protected $player;

	public function __construct(Main $plugin, Player $player)
	{
		parent::__construct($plugin);
		$this->player = $player;
	}

	public function onRun(int $currentTick)
	{
		$level = $this->owner->getServer()->getLevelByName("corepvp");
		$this->player->teleport(new Position(255, 8, 257, $level));
		$this->player->setHealth(20);
		$this->player->setMaxHealth(20);
		$this->player->setFood(20);
		$this->player->getArmorInventory()->clearAll(true);
		$this->player->removeAllEffects();
		$this->player->getInventory()->clearAll(true);
		$this->player->sendMessage("§aテレポートしました。");
	}
}