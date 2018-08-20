<?php

/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/23
 * Time: 15:05
 */

namespace Core\Game\Athletic;

use Core\Main;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\utils\Config;

class AthleticCore
{
	private $player_data = [];
	protected static $json;

	public function __construct()
	{
		self::$json = new Config(Main::$datafolder . 'athletic.json', Config::JSON);
	}

	public function touch(PlayerInteractEvent $event): void
	{
		$player = $event->getPlayer();
		if ($player->getLevel()->getName() === "athletic") {
			if ($event->getBlock() === 133) {
				$array_data = self::$json->get($this->getAthleticData($event));
				$this->player_data[$player->getName()] = [
					"Athletic" => $this->getAthleticData($event),
					"X" => $array_data["SpawnX"],
					"Y" => $array_data["SpawnY"],
					"Z" => $array_data["SpawnZ"],
					"StartTime" => microtime(true)
				];
				if ($this->isAthleticFinish($event, $player)) {
					$time = microtime(true) - $this->player_data[$player->getName()]["StartTime"];
					$player->sendMessage($time);
					unset($this->player_data[$player->getName()]);
					return;
				}
			}
		}
	}

	public function getAthleticData(PlayerInteractEvent $event): string
	{
		return array_search($event->getTouchVector(), self::$json->getAll(true));
	}

	public function isAthleticFinish(PlayerInteractEvent $event, Player $player): bool
	{
		if (!isset($this->player_data)) return false;
		if ($event->getTouchVector() == $this->getAthleticFinishPos($player)) {
			return true;
		}
		return false;
	}

	public function getAthleticFinishPos(Player $player)
	{
		return self::$json->get($this->player_data[$player->getName()]["Athletic"])["Finish"];
	}

	public static function setAthletic(string $athle_name, string $author, $x, $y, $z, Vector3 $startPos, Vector3 $finishPos, int $prize): bool
	{
		if (self::$json->exists($athle_name)) {
			return false;
		}
		self::$json->set($athle_name, [
			"Author" => $author,
			"SpawnX" => $x,
			"SpawnY" => $y,
			"SpawnZ" => $z,
			"StartPos" => $startPos,
			"FinishPos" => $finishPos,
			"Prize" => $prize
		]);
		return true;
	}

	public function loop(PlayerMoveEvent $event): void
	{
		$player = $event->getPlayer();
		if ($player->getLevel()->getName() === "athletic") {
			if ($event->getTo()->getFloorY() < 4) {
				if (isset($this->player_data[$player->getName()])) {
					$pos = $this->player_data[$player->getName()];
					$player->teleport(new Position($pos["X"], $pos["Y"], $pos["Z"], $player->getLevel()->getName()));
					$player->sendMessage("失敗");
					unset($this->player_data[$player->getName()]);
				}
			}
		}
	}

	public function onQuit(PlayerQuitEvent $event): void
	{
		$player = $event->getPlayer();
		if (isset($this->player_data[$player->getName()])) {
			unset($this->player_data[$player->getName()]);
		}
	}
}
