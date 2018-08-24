<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/08
 * Time: 22:44
 */

namespace Core\Task\Teleport;


use Core\Main;
use Core\Task\PluginTask;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class TeleportSurvivalSpawnTask extends PluginTask
{
	protected static $x, $y, $z, $player;

	public function __construct(Plugin $plugin, Player $player, float $x, float $y, float $z)
	{
		parent::__construct($plugin);
		self::$player = $player;
		self::$x = $x;
		self::$y = $y;
		self::$z = $z;
	}

	public function onRun(int $currentTick)
	{
		self::$player->teleport(new Position(self::$x, self::$y, self::$z, Main::$instance->getServer()->getLevelByName('Survival')));
	}
}