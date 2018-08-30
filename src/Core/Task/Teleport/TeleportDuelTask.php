<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/30
 * Time: 14:39
 */

namespace Core\Task\Teleport;


use Core\Task\PluginTask;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class TeleportDuelTask extends PluginTask
{
	private $player;
	public function __construct(Plugin $plugin, Player  $player)
	{
		parent::__construct($plugin);
		$this->player = $player;
	}

	public function onRun(int $currentTick)
	{
		$this->player->teleport(new Position(254, 4, 254, $this->owner->getServer()->getLevelByName("duel")));
		$this->player->setSpawn(new Position(254, 4, 254, $this->owner->getServer()->getLevelByName("duel")));
		$this->player->setHealth(20);
		$this->player->getArmorInventory()->clearAll(true);
		$this->player->setMaxHealth(20);
		$this->player->setFood(20);
		$this->player->removeAllEffects();
		$this->player->getInventory()->clearAll(true);
		$this->player->sendMessage("§aテレポートしました。");
	}
}