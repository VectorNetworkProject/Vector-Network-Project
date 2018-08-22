<?php
/**
 * Created by PhpStorm.
 * User: PCink
 * Date: 2018/08/22
 * Time: 13:10
 */

namespace Core\Task\Teleport;


use Core\Main;
use Core\Task\PluginTask;
use pocketmine\plugin\Plugin;

class TeleportDuelStageTask extends PluginTask
{
	protected $players;
	public function __construct(Plugin $plugin, array $players)
	{
		parent::__construct($plugin);
		$this->players = $players;
	}
	public function onRun(int $currentTick)
	{
		foreach ($this->players as $playerName) {
			$player = Main::$instance->getServer()->getPlayer($playerName);
			switch ($currentTick) {
				case 120:
					$player->setImmobile(false);
					$player->addTitle("§cSTART", "§cMode: Duel(1v1)", 20, 20, 20);
					break;
				case 100:
					$player->addTitle("§c1", "", 20, 20, 20);
					break;
				case 80:
					$player->addTitle("§e2", "", 20, 20, 20);
					break;
				case 60:
					$player->addTitle("§e3", "", 20, 20, 20);
					break;
				case 40:
					$player->addTitle("§a4", "", 20, 20, 20);
					break;
				case 20:
					$player->addTitle("§a5", "", 20, 20, 20);
					break;
			}
		}
	}
}