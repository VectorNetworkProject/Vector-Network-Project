<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 13:19
 */

namespace Core\Event;

use Core\Main;
use Core\Player\KillSound;
use Core\Player\Level;
use Core\Player\MazaiPoint;
use Core\Player\Money;
use Core\Player\Rank;
use Core\Player\Tag;
use Core\Task\LevelCheckingTask;
use Core\Task\Teleport\TeleportAthleticTask;
use Core\Task\Teleport\TeleportFFAPvPTask;
use Core\Task\Teleport\TeleportLobbyTask;
use Core\Task\Teleport\TeleportSpeedCorePvPTask;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class DataPacketReceive
{

	const BUY_SUCCESS = "§7[§a成功§7] §a購入に成功しました。";
	const BUY_ERROR = "§7[§c失敗§7] §r§6V§bN§eCoin§cがたりません。";
	const MAZAI_SUCCESS = "§7[§a成功§7] §a購入に成功しました。";
	const MAZAI_ERROR = "§7[§c失敗§7] §r§aMAZAI§cが足りません";

	protected $plugin;
	protected $money;
	protected $rank;
	protected $tag;
	protected $level;
	protected $killsound;
	protected $speedcorepvp;
	protected $mazai;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->money = new Money();
		$this->rank = new Rank($this->plugin);
		$this->tag = new Tag();
		$this->level = new Level();
		$this->killsound = new KillSound($this->plugin);
		$this->mazai = new MazaiPoint();
	}

	public function event(DataPacketReceiveEvent $event)
	{
		$packet = $event->getPacket();
		$player = $event->getPlayer();
		if ($packet instanceof ModalFormResponsePacket) {
			if ($packet->formId === 45661984) {
				if (($data = json_decode($packet->formData)) === null) {
					return;
				}
				switch ($data[0]) {
					case 0:
						if ($this->money->reduceMoney($player->getName(), 1500000)) {
							$player->sendMessage(self::BUY_SUCCESS);
							$this->rank->setRank($player->getName(), 1);
						} else {
							$player->sendMessage(self::BUY_ERROR);
						}
						break;
					case 1:
						if ($this->money->reduceMoney($player->getName(), 1000000)) {
							$player->sendMessage(self::BUY_SUCCESS);
							$this->rank->setRank($player->getName(), 2);
						} else {
							$player->sendMessage(self::BUY_ERROR);
						}
						break;
					case 2:
						if ($this->money->reduceMoney($player->getName(), 700000)) {
							$player->sendMessage(self::BUY_SUCCESS);
							$this->rank->setRank($player->getName(), 3);
						} else {
							$player->sendMessage(self::BUY_ERROR);
						}
						break;
					case 3:
						if ($this->money->reduceMoney($player->getName(), 500000)) {
							$player->sendMessage(self::BUY_SUCCESS);
							$this->rank->setRank($player->getName(), 4);
						} else {
							$player->sendMessage(self::BUY_ERROR);
						}
						break;
					case 4:
						if ($this->money->reduceMoney($player->getName(), 300000)) {
							$player->sendMessage(self::BUY_SUCCESS);
							$this->rank->setRank($player->getName(), 5);
						} else {
							$player->sendMessage(self::BUY_ERROR);
						}
						break;
					case 5:
						if ($this->money->reduceMoney($player->getName(), 100000)) {
							$player->sendMessage(self::BUY_SUCCESS);
							$this->rank->setRank($player->getName(), 6);
						} else {
							$player->sendMessage(self::BUY_ERROR);
						}
						break;
					case 6:
						if ($this->money->reduceMoney($player->getName(), 50000)) {
							$player->sendMessage(self::BUY_SUCCESS);
							$this->rank->setRank($player->getName(), 7);
						} else {
							$player->sendMessage(self::BUY_ERROR);
						}
						break;
					default:
						$player->sendMessage("§7[§c失敗§7] §c購入をキャンセルしました。");
						break;
				}
			}
			if ($packet->formId === 8489612) {
				if (($data = json_decode($packet->formData)) === null) {
					return;
				}
				if (empty($data)) {
					$player->sendMessage("§7[§c失敗§7] §cタグ名を記入して下さい。");
				} else {
					if (empty($data[2])) {
						$tag = "NoTag";
					} else {
						$tag = $data[2];
					}
					if ($this->money->reduceMoney($player->getName(), 1000)) {
						$player->sendMessage("§7[§b情報§7] §6V§bN§eCoin§7を§61000§7消費しました。");
						$this->tag->setTag($player, $tag, $data[1]);
					} else {
						$player->sendMessage(self::BUY_ERROR);
					}
				}
			}
			if ($packet->formId === 45786154) {
				if (($data = json_decode($packet->formData)) === null) {
					return;
				}
				switch ($data[0]) {
					case 0:
						if ($player->getLevel()->getName() === "lobby") {
							$player->sendMessage("§c既にロビーに居ます。");
						} else {
							$player->sendMessage("§e10秒後テレポートします。");
							$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportLobbyTask($this->plugin, $player), 10 * 20);
						}
						break;
					case 1:
						if ($player->getLevel()->getName() === "ffapvp") {
							$player->sendMessage("§c既にFFAPvPに居ます");
						} else {
							if ($player->getLevel()->getName() === "lobby") {
								$player->teleport(new Position(254, 107, 254, $this->plugin->getServer()->getLevelByName("ffapvp")));
								$player->setSpawn(new Position(254, 107, 254, $this->plugin->getServer()->getLevelByName("ffapvp")));
								$player->sendMessage("§aテレポートしました。");
							} else {
								$player->sendMessage("§e10秒後テレポートします。");
								$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportFFAPvPTask($this->plugin, $player), 10 * 20);
							}
						}
						break;
					case 2:
						if ($player->getLevel()->getName() === "corepvp") {
							$player->sendMessage("§c既にCorePvPに居ます");
						} else {
							if ($player->getLevel()->getName() === "lobby") {
								$player->teleport(new Position(255, 8, 257, $this->plugin->getServer()->getLevelByName("corepvp")));
								$player->sendMessage("§aテレポートしました。");
							} else {
								$player->sendMessage("§e10秒後テレポートします。");
								$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportSpeedCorePvPTask($this->plugin, $player), 10 * 20);
							}
						}
						break;
					case 3:
						if ($player->isOp()) {
							if ($player->getLevel()->getName() === "athletic") {
								$player->sendMessage("§c既にAthleticに居ます");
							} else {
								if ($player->getLevel()->getName() === "lobby") {
									$player->teleport(new Position(254, 4, 254, $this->plugin->getServer()->getLevelByName("athletic")));
									$player->sendMessage("§aテレポートしました。");
								} else {
									$player->sendMessage("§e10秒後テレポートします。");
									$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportAthleticTask($this->plugin, $player), 10 * 20);
								}
							}
						} else {
							$player->sendMessage("現在開発者のみがテレポートする事が出来ます。");
						}
						break;
				}
			}
			if ($packet->formId === 94572154) {
				if (($data = json_decode($packet->formData)) === null) {
					return;
				}
				switch ($data[0]) {
					case 0:
						$this->killsound->setKillSound($player, 0);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを無効にしました。");
						break;
					case 1:
						$this->killsound->setKillSound($player, 1);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【チーン】に設定しました。");
						break;
					case 2:
						$this->killsound->setKillSound($player, 2);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【1UP】に設定しました。");
						break;
					case 3:
						$this->killsound->setKillSound($player, 3);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【骨が折れる音】に設定しました。");
						break;
					case 4:
						$this->killsound->setKillSound($player, 4);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【デデドン】に設定しました。");
						break;
					case 5:
						$this->killsound->setKillSound($player, 5);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【ピチューン】に設定しました。");
						break;
					case 6:
						$this->killsound->setKillSound($player, 6);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【ブスッ】に設定しました。");
						break;
					case 7:
						$this->killsound->setKillSound($player, 7);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【許してくれたまえ】に設定しました。");
						break;
					case 8:
						$this->killsound->setKillSound($player, 8);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【さっさと逃げればいいものを】に設定しました。");
						break;
				}
			}
			if ($packet->formId === 75498654) {
				if (($data = json_decode($packet->formData)) === null) {
					return;
				}
				switch ($data) {
					case 0:
						if ($this->money->reduceMoney($player->getName(), 10000)) {
							$player->sendMessage(self::BUY_SUCCESS);
							$this->mazai->addMazai($player->getName(), 1);
						} else {
							$player->sendMessage(self::BUY_ERROR);
						}
						break;
				}
			}
			if ($packet->formId === 8168764) {
				if (($data = json_decode($packet->formData)) === null) {
					return;
				}
				switch ($data) {
					case 0:
						if ($this->mazai->reduceMazai($player->getName(), 1)) {
							$player->sendMessage(self::MAZAI_SUCCESS);
							$this->level->addExp($player->getName(), 300);
							$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
						} else {
							$player->sendMessage(self::MAZAI_ERROR);
						}
						break;
					case 1:
						if ($this->mazai->reduceMazai($player->getName(), 1)) {
							$player->sendMessage(self::MAZAI_SUCCESS);
							$this->money->addMoney($player->getName(), 10000);
						} else {
							$player->sendMessage(self::MAZAI_ERROR);
						}
						break;
				}
			}
		}
	}
}
