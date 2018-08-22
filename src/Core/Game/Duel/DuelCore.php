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
use Core\Task\Teleport\TeleportDuelStageTask;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\level\Position;
use pocketmine\Player;

class DuelCore
{
	const DUEL_MAX_PLAYER = 2;
	const DUEL_PLAYER = 0;
	const LEVEL_NAME = "duel";

	protected $player = 0;
	protected $joined = [];
	protected $status = false;

	public function DuelJoin(Player $player)
	{
		if ($player->getLevel()->getName() === self::LEVEL_NAME) return;
		if ($this->status === true) {
			$player->sendMessage("§c現在ゲーム中です。");
		}
		if (isset($this->joined[$player->getName()])) {
			$player->sendMessage("§c既にDuelに参加しています。");
		} else {
			if ($this->player === 1) {
				$this->status = true;
				$player->setImmobile(true);
				Main::$instance->getScheduler()->scheduleDelayedTask(new TeleportDuelStageTask(Main::$instance, $this->joined), 6*20);
			}
			$this->joined[$player->getName()] = $player->getName();
			$player->sendMessage("§aDuelに参加しました。");
			$player->setImmobile(true);
			$this->player++;
		}
	}

	public function endGame(Player $player)
	{
		if ($this->status === false) return;
		foreach ($this->joined as $name) {
			if ($name === $player->getName()) {
				$player->addTitle("§cYOU DIED", "§cあなたは勝負に負けてしまった。", 20, 40, 20);
				self::AddLose($name);
			} else {
				Main::$instance->getServer()->getPlayer($name)->addTitle("§6VICTORY", "§6あなたは勝負に買った", 20, 40, 20);
				self::sendMessage("§7[§cDuel§7] §e" . $name . "§aが勝利しました。");
				self::AddWin($name);
			}
			Main::$instance->getServer()->getPlayer($name)->setHealth(20);
			Main::$instance->getServer()->getPlayer($name)->setFood(20);
			Main::$instance->getServer()->getPlayer($name)->getInventory()->clearAll();
			Main::$instance->getServer()->getPlayer($name)->teleport(new Position(257, 8, 257, Main::$instance->getServer()->getLevelByName(self::LEVEL_NAME)));
		}
		self::Reload();
		unset($this->joined);
		$this->status = false;
		$this->player = 0;
	}

	public function Quit(PlayerQuitEvent $event)
	{
		$player = $event->getPlayer();
		if (isset($this->joined[$player->getName()])) {
			$this->endGame($player);
		}
	}

	public function Join(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if ($player->getLevel()->getName() !== self::LEVEL_NAME) return;
		if ($block->getId() === 42) {
			self::DuelJoin($player);
		}
	}

	public function Leave(EntityLevelChangeEvent $event)
	{
		$entity = $event->getEntity();
		if (!$entity instanceof Player) return;
		if ($event->getOrigin()->getName() === self::LEVEL_NAME) {
			unset($this->joined[$entity->getName()]);
		}
	}

	public function CancelCommand(PlayerCommandPreprocessEvent $event)
	{
		if (isset($this->joined[$event->getPlayer()->getName()])) {
			if ($event->getMessage() === "/selectgame") return;
		}
	}

	/**
	 * @param string $name
	 */
	private static function AddWin(string $name)
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('DUEL');
		$data['win']++;
		$datafile->write('DUEL', $data);
	}

	/**
	 * @param string $name
	 */
	private static function AddLose(string $name)
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('DUEL');
		$data['lose']++;
		$datafile->write('DUEL', $data);
	}

	/**
	 * @param string $message
	 */
	private static function sendMessage(string $message)
	{
		foreach (Main::$instance->getServer()->getOnlinePlayers() as $player) {
			if (!$player->getLevel()->getName() === self::LEVEL_NAME) return;
			$player->sendMessage($message);
		}
	}

	private static function Reload()
	{
		Main::$instance->getServer()->unloadLevel(Main::$instance->getServer()->getLevelByName(self::LEVEL_NAME));
		Main::$instance->getServer()->loadLevel(self::LEVEL_NAME);
		$level = Main::$instance->getServer()->getLevelByName(self::LEVEL_NAME);
		$level->setTime(6000);
		$level->stopTime();
	}
}