<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/08
 * Time: 22:44
 */

namespace Core\Task\Teleport;


use Core\Task\PluginTask;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class TeleportSurvivalSpawnTask extends PluginTask
{
	protected $player, $spawn;
	public function __construct(Plugin $plugin, Player $player, array $spawn)
	{
		parent::__construct($plugin);
		$this->player = $player;
		$this->spawn = $spawn;
	}

	public function onRun(int $currentTick)
	{
		$this->player->teleport(new Position($this->spawn['x'], $this->spawn['y'], $this->spawn['z'], $this->owner->getServer()->getLevelByName('Survival')));
	}
}