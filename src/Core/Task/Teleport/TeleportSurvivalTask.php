<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/08
 * Time: 9:22
 */

namespace Core\Task\Teleport;


use Core\Game\Survival\SurvivalCore;
use Core\Main;
use Core\Task\PluginTask;
use pocketmine\level\Position;
use pocketmine\Player;

class TeleportSurvivalTask extends PluginTask
{
	protected $player;

	public function __construct(Main $plugin, Player $player)
	{
		parent::__construct($plugin);
		$this->player = $player;
	}

	public function onRun(int $currentTick)
	{
		$level = $this->owner->getServer()->getLevelByName("Survival");
		SurvivalCore::Teleport($this->player);
		$this->player->setSpawn(new Position(225, 243, 256, $level));
		$this->player->setHealth(20);
		$this->player->setMaxHealth(20);
		$this->player->setFood(20);
		$this->player->getArmorInventory()->clearAll(true);
		$this->player->removeAllEffects();
		$this->player->getInventory()->clearAll(true);
		$this->player->sendMessage("§aテレポートしました。");
	}
}