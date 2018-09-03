<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/21
 * Time: 21:32
 */

namespace Core\Game\Duel;


use Core\DataFile;
use Core\Main;
use Core\Player\Level;
use Core\Player\Money;
use Core\Task\Teleport\TeleportDuelStageTask;
use Core\Game\Duel\stages\Stage1;
use pocketmine\block\Block;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\level\Position;
use pocketmine\Player;

class DuelCore
{

	const DUEL_MAX_PLAYER = 2;
	const LEVEL_NAME = "duel";

	protected $stages = [];
	protected $datas = [];
	protected $players = [];
	protected $playerslist = [];//Player Class
	protected $status = [];
	protected $stage, $usedStage;
	public static $gameId = 0, $money, $level;

	public function __construct()
	{
		$this->stages = [
			new Stage1(),
			//new Stage2(),
			//new Stage3(),
			//new Stage4(),
			//new Stage5(),
			//new Stage6(),
		];
		self::$money = new Money();
		self::$level = new Level();
	}

	public function create(int $gameId)
	{
		$this->players[$gameId] = 0;
		$this->playerslist[$gameId] = [];
		$this->status[$gameId] = false;
	}

	public function DuelJoin(Player $player, int $gameId = 0)
	{
		$name = $player->getName();
		if ($player->getLevel()->getName() !== self::LEVEL_NAME) return;
		if (!$this->status[$gameId]) {
			if (empty($this->datas[$name])) {
				$data["gameId"] = $gameId;
				$this->datas[$name] = $data;
				$player->sendMessage("§aDuelに参加しました。");
				$player->setImmobile(true);
				$this->players[$gameId]++;
				$this->playerslist[$gameId][$player->getId()] = $player;
				if ($this->players[$gameId] == self::DUEL_MAX_PLAYER) {
					$this->randStage($gameId);
					$this->status[$gameId] = true;
					$this->wattingGame($gameId);
				}
			} else {
				$player->sendMessage("§c既にDuelに参加しています。");
			}
		} else {
			$gameId = self::$gameId++;
			$this->create($gameId);
			$this->DuelJoin($player, $gameId);
		}
	}

	public function DuelQuit(Player $player)
	{
		$gameId = self::getGameIdByPlayer($player);
		if ($gameId == null) return;
		if ($this->status[$gameId]) {
			$this->endGame($player);
		} else {
			$name = $player->getName();
			unset($this->datas[$name]);
			unset($this->playerslist[$gameId][$name]);
			$this->players[$gameId]--;
			$player->setHealth(20);
			$player->setFood(20);
			$player->getInventory()->clearAll();
			$player->teleport(new Position(257, 8, 257, Main::$instance->getServer()->getLevelByName(self::LEVEL_NAME)));
		}
	}

	public function wattingGame(int $gameId)
	{
		$c = 0;
		$spawnPosition = $this->stage[$gameId][1]->getSpawnPosition();
		foreach ($this->playerslist[$gameId] as $entityId => $player) {
			$player->teleport($spawnPosition[$c]);
			$c++;
		}
		Main::$instance->getScheduler()->scheduleDelayedTask(new TeleportDuelStageTask(Main::$instance, $this, $gameId), 6 * 20);
	}

	public function startGame(int $gameId, int $count)
	{
		if ($count % 20 == 0) {
			$time = 5 - $count % 20;
			foreach ($this->playerslist[$gameId] as $entityId => $player) {
				switch ($time) {
					case 5:
						$player->addTitle("§a5", "", 20, 20, 20);
						break;
					case 4:
						$player->addTitle("§a4", "", 20, 20, 20);
						break;
					case 3:
						$player->addTitle("§e3", "", 20, 20, 20);
						break;
					case 2:
						$player->addTitle("§e2", "", 20, 20, 20);
						break;
					case 1:
						$player->addTitle("§c1", "", 20, 20, 20);
						break;
					case 0:
						$player->setImmobile(false);
						$player->addTitle("§cSTART", "§cMode: Duel(1v1)", 20, 20, 20);
						break;

				}
			}
		}
	}

	public function endGame(Player $player)
	{
		$gameId = self::getGameIdByPlayer($player);
		if ($gameId == null) return;
		if (!$this->status[$gameId]) return;
		foreach ($this->playerslist[$gameId] as $entityId => $players) {
			if ($player === $players) {
				$players->addTitle("§cYOU DIED", "§cあなたは勝負に負けてしまった。", 20, 40, 20);
				self::$level->LevelSystem($players);
				self::AddLose($players);
			} else {
				$players->addTitle("§6VICTORY", "§6あなたは勝負に買った", 20, 40, 20);
				$this->broatcastMessage($gameId, "§7[§cDuel§7] §e" . $player->getName() . "§aが勝利しました。");
				self::$money->addMoney($players->getName(), 100);
				self::$level->LevelSystem($players);
				self::AddWin($players);
			}
			$players->setHealth(20);
			$players->setFood(20);
			$players->getInventory()->clearAll();
			$players->teleport(new Position(257, 8, 257, Main::$instance->getServer()->getLevelByName(self::LEVEL_NAME)));
		}
		$this->reset($gameId);
	}

	public function Join(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if ($player->getLevel()->getName() !== self::LEVEL_NAME) return;
		if ($block->getId() === Block::IRON_BLOCK) {
			$this->DuelJoin($player);
			//$this->DuelJoin($player, self::$gameId);
		}
	}

	public function Quit(PlayerQuitEvent $event)
	{
		$player = $event->getPlayer();
		if (isset($this->datas[$player->getName()])) {
			$this->DuelQuit($player);
		}
	}

	public function CancelCommand(PlayerCommandPreprocessEvent $event)
	{
		$player = $event->getPlayer();
		if (isset($this->datas[$player->getName()])) {
			if ($event->getMessage() === "/selectgame") return;
		}
	}

	public function randStage(int $gameId)
	{
		$rand = mt_rand(0, count($this->stages) - 1);
		if (empty($this->usedStage[$rand])) {
			$this->usedStage[$rand] = $gameId;
			$this->stage[$gameId] = [$rand, $this->stages[$rand]];
		} else {
			$this->randStage($gameId);
		}
	}

	public function reset(int $gameId)
	{
		foreach ($this->playerslist[$gameId] as $entityId => $player) {
			unset($this->datas[$player->getName()]);
		}
		$this->playerslist[$gameId] = [];
		$this->status[$gameId] = false;
		$this->players[$gameId] = [];

		$stageId = $this->stage[$gameId][0];
		unset($this->usedStage[$stageId]);
	}

	public function broatcastMessage(int $gameId, string $message)
	{
		foreach ($this->playerslist[$gameId] as $entityId => $player) {
			$player->sendMessage($message);
		}
	}

	private static function AddWin(Player $player)
	{
		$name = $player->getName();
		$datafile = new DataFile($name);
		$data = $datafile->get('DUEL');
		$data['win']++;
		$datafile->write('DUEL', $data);
	}

	private static function AddLose(Player $player)
	{
		$name = $player->getName();
		$datafile = new DataFile($name);
		$data = $datafile->get('DUEL');
		$data['lose']++;
		$datafile->write('DUEL', $data);
	}

	public function getGameIdByPlayer(Player $player)
	{
		$name = $player->getName();
		if (empty($this->datas[$name])) return null;
		return $this->datas[$name]["gameId"];
	}
}