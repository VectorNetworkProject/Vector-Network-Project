<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 21:12
 */

namespace Core\Task\Teleport;

use Core\Main;
use Core\Task\PluginTask;
use pocketmine\level\Position;
use pocketmine\Player;

class TeleportFFAPvPTask extends PluginTask
{
	protected $player, $plugin;

	public function __construct(Main $plugin, Player $player)
	{
		parent::__construct($plugin);
		$this->plugin = $plugin;
		$this->player = $player;
	}

	public function onRun(int $currentTick)
	{
		$this->player->teleport(new Position(254, 107, 254, $this->owner->getServer()->getLevelByName("ffapvp")));
		$this->player->setSpawn(new Position(254, 107, 254, $this->owner->getServer()->getLevelByName("ffapvp")));
		$this->player->setHealth(20);
		$this->player->getArmorInventory()->clearAll(true);
		$this->player->setMaxHealth(20);
		$this->player->setFood(20);
		$this->player->removeAllEffects();
		$this->player->getInventory()->clearAll(true);
		$this->player->sendMessage("§aテレポートしました。");
	}
}
