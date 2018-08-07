<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/24
 * Time: 22:36
 */

namespace Core\Game\SpeedCorePvP;


use Core\DataFile;
use Core\Main;
use Core\Player\Level;
use Core\Player\Money;
use Core\Task\AutosetBlockTask;
use Core\Task\LevelCheckingTask;
use Core\Task\RemoveArmorTask;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\item\Armor;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\utils\Color;

class SpeedCorePvPCore
{
	protected $plugin;
	protected $bluecolor;
	protected $redcolor;
	protected $bluehp = 100;
	protected $redhp = 100;
	protected $bluecount = 0;
	protected $redcount = 0;
	protected $team = [];
	protected $gamemode = false;
	protected $money;
	protected $level;
	protected $fieldname = "corepvp";
	protected $point = [
		"blue.core" => [
			"x" => 52,
			"y" => 61,
			"z" => -100
		],
		"blue.spawn" => [
			"x" => 52,
			"y" => 66,
			"z" => -100
		],
		"red.core" => [
			"x" => 235,
			"y" => 61,
			"z" => 11
		],
		"red.spawn" => [
			"x" => 235,
			"y" => 66,
			"z" => 11
		]
	];

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->bluecolor = new Color(0, 0, 255);
		$this->redcolor = new Color(255, 0, 0);
		$this->money = new Money();
		$this->level = new Level();
	}

	public static $blockids = [
		Block::IRON_ORE => 20,
		Block::GOLD_ORE => 20,
		Block::COAL_ORE => 15,
		Block::DIAMOND_ORE => 60,
		Block::LOG => 10,
		Block::MELON_BLOCK => 10
	];

	/**
	 * @return bool
	 */
	public function getGameMode(): bool
	{
		return $this->gamemode ? true : false;
	}

	/**
	 * @param bool $bool
	 */
	public function setGameMode(bool $bool)
	{
		if ($bool) {
			$this->gamemode = true;
		} else {
			$this->gamemode = false;
		}
	}

	/**
	 * @param int $teamid
	 * @param int $hp
	 */
	public function setHP(int $teamid, int $hp)
	{
		switch ($teamid) {
			case 1:
				$this->redhp = $hp;
				break;
			case 2:
				$this->bluehp = $hp;
				break;
			default:
				return;
				break;
		}
	}

	/**
	 * @param int $teamid
	 * @return int
	 */
	public function getHP(int $teamid): int
	{
		switch ($teamid) {
			case 1:
				return $this->redhp;
				break;
			case 2:
				return $this->bluehp;
				break;
			default:
				return 0;
				break;
		}
	}

	/**
	 * @param int $teamid
	 * @param int $count
	 */
	public function setPlayerCount(int $teamid, int $count)
	{
		switch ($teamid) {
			case 1:
				$this->redcount = $count;
				break;
			case 2:
				$this->bluecount = $count;
				break;
			default:
				return;
				break;
		}
	}

	/**
	 * @param int $teamid
	 */
	public function AddPlayerCount(int $teamid)
	{
		switch ($teamid) {
			case 1:
				$this->redcount++;
				break;
			case 2:
				$this->bluecount++;
				break;
			default:
				return;
				break;
		}
	}

	/**
	 * @param int $teamid
	 * @return int
	 */
	public function getPlayerCount(int $teamid): int
	{
		switch ($teamid) {
			case 1:
				return $this->redcount;
				break;
			case 2:
				return $this->bluecount;
				break;
			default:
				return 0;
				break;
		}
	}

	/**
	 * @param Player $player
	 */
	public function setSpawn(Player $player)
	{
		$level = $this->plugin->getServer()->getLevelByName($this->fieldname);
		$red = $this->point["red.spawn"];
		$blue = $this->point["blue.spawn"];
		if ($this->team[$player->getName()] === "Red") {
			$player->setSpawn(new Position($red["x"], $red["y"], $red["z"], $level));
			$player->teleport(new Position($red["x"], $red["y"], $red["z"], $level));
		} else {
			$player->setSpawn(new Position($blue["x"], $blue["y"], $blue["z"], $level));
			$player->teleport(new Position($blue["x"], $blue["y"], $blue["z"], $level));
		}
	}

	/**
	 * @param Player $player
	 * @param Block $block
	 */
	public function GameJoin(Player $player, Block $block)
	{
		if ($player->getLevel()->getName() === $this->fieldname) {
			if ($block->getId() === Block::EMERALD_BLOCK) {
				$this->setGameMode(true);
				if (isset($this->team[$player->getName()])) {
					$player->sendMessage("§cあなたは既にチームに所属しています。");
					return;
				}

				if ($this->redcount < $this->bluecount) {
					$this->team[$player->getName()] = "Red";
					$this->AddPlayerCount(1);
					$this->setSpawn($player);
					$this->Kit($player);
					$player->sendMessage("§7あなたは §cRed §7Teamになりました。");
					return;
				} else {
					$this->team[$player->getName()] = "Blue";
					$this->AddPlayerCount(2);
					$this->setSpawn($player);
					$this->Kit($player);
					$player->sendMessage("§7あなたは §9Blue §7Teamになりました。");
					return;
				}
			}
		}
	}

	/**
	 * @param Player $player
	 */
	public function GameQuit(Player $player)
	{
		if (isset($this->team[$player->getName()])) {
			if ($this->team[$player->getName()] === "Red") {
				unset($this->team[$player->getName()]);
				$this->ReducePlayerCount(1);
				$player->sendMessage("§cRed §7Teamから退出しました。");
			} elseif ($this->team[$player->getName()] === "Blue") {
				unset($this->team[$player->getName()]);
				$this->ReducePlayerCount(2);
				$player->sendMessage("§9Blue §7Teamから退出しました。");
			}
		}
	}

	/**
	 * @param BlockBreakEvent $event
	 */
	public function DropItem(BlockBreakEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if ($player->getLevel()->getName() === $this->fieldname) {
			switch ($block->getId()) {
				case Block::IRON_ORE:
					$event->setDrops([Item::get(Item::IRON_INGOT, 0, 1)]);
					break;
				case Block::GOLD_ORE:
					$event->setDrops([Item::get(Item::GOLD_INGOT, 0, 1)]);
					break;
				case Block::COAL_ORE:
					$event->setDrops([Item::get(Item::COAL, 0, 1)]);
					break;
				case Block::DIAMOND_ORE:
					$event->setDrops([Item::get(Item::DIAMOND, 0, 1)]);
					break;
				case Block::MELON_BLOCK:
					$event->setDrops([Item::get(Item::MELON, 0, 16)]);
					break;
				case Block::LOG:
					$event->setDrops([Item::get(Item::LOG, 0, 1)]);
					break;
			}
			if (isset(self::$blockids[$block->getId()])) {
				if (isset($event->getDrops()[0])) {
					$player->getInventory()->addItem($event->getDrops()[0]);
					$event->setDrops([Item::get(Item::AIR, 0, 0)]);
					$this->plugin->getScheduler()->scheduleDelayedTask(new AutosetBlockTask($this->plugin, $block), self::$blockids[$block->getId()] * 20);
				}
			}
		}
	}

	/**
	 * @param int $teamid
	 */
	public function ReducePlayerCount(int $teamid)
	{
		switch ($teamid) {
			case 1:
				$this->redcount--;
				break;
			case 2:
				$this->bluecount--;
				break;
			default:
				return;
				break;
		}
	}

	public function LevelChange(EntityLevelChangeEvent $event)
	{
		$entity = $event->getEntity();
		if ($event->getOrigin()->getName() === $this->fieldname) {
			if ($entity instanceof Player) {
				$this->GameQuit($entity->getPlayer());
				$entity->getArmorInventory()->clearAll(true);
				$this->plugin->getScheduler()->scheduleDelayedTask(new RemoveArmorTask($this->plugin, $entity), 20);
			}
		}
	}

	/**
	 * @param Player $player
	 */
	public function Kit(Player $player)
	{
		$armors = [
			"leather_cap" => Item::get(Item::LEATHER_CAP, 0, 1),
			"leather_tunic" => Item::get(Item::LEATHER_TUNIC, 0, 1),
			"leather_pants" => Item::get(Item::LEATHER_PANTS, 0, 1),
			"leather_boots" => Item::get(Item::LEATHER_BOOTS, 0, 1)
		];
		$weapons = [
			"stone_sword" => Item::get(Item::STONE_SWORD, 0, 1),
			"bow" => Item::get(Item::BOW, 0, 1),
			"gold_pickaxe" => Item::get(Item::GOLD_PICKAXE, 0, 1),
			"stone_axe" => Item::get(Item::STONE_AXE, 0, 1),
			"stone_shovel" => Item::get(Item::STONE_SHOVEL, 0, 1)
		];
		$this->team[$player->getName()] === "Red" ? $teamColor = $this->redcolor : $teamColor = $this->bluecolor;
		foreach ($armors as $armor) {
			if ($armor instanceof Durable and $armor instanceof Armor) {
				$armor->setUnbreakable(true);
				$armor->setCustomColor($teamColor);
			}
		}
		foreach ($weapons as $weapon) {
			if ($weapon instanceof Durable) {
				$weapon->setUnbreakable(true);
			}
		}
		$armor = $player->getArmorInventory();
		$armor->setHelmet($armors['leather_cap']);
		$armor->setChestplate($armors['leather_tunic']);
		$armor->setLeggings($armors['leather_pants']);
		$armor->setBoots($armors['leather_boots']);
		$player->getInventory()->addItem($weapons['stone_sword']);
		$player->getInventory()->addItem($weapons['bow']);
		$player->getInventory()->addItem($weapons['gold_pickaxe']);
		$player->getInventory()->addItem($weapons['stone_axe']);
		$player->getInventory()->addItem($weapons['stone_shovel']);
		$player->getInventory()->addItem(Item::get(Item::ARROW, 0, 64));
	}

	/**
	 * @param Player $player
	 */
	public function Respawn(Player $player)
	{
		if ($player->getLevel()->getName() === $this->fieldname) {
			$this->Kit($player);
			$player->addTitle("§cYou are dead", "§cあなたは死んでしまった", 20, 40, 20);
		}
	}

	public function TeamChat(PlayerChatEvent $event)
	{
		if ($event->getPlayer()->getLevel()->getName() === $this->fieldname) {
			if (isset($this->team[$event->getPlayer()->getName()])) {
				if (strpos($event->getMessage(), '@') !== false or strpos($event->getMessage(), '＠') !== false) {
					$event->setCancelled(true);
					foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
						if (isset($this->team[$player->getName()])) {
							if ($this->team[$player->getName()] === $this->team[$event->getPlayer()->getName()]) {
								$message = str_replace(['＠', '@'], '', $event->getMessage());
								$player->sendMessage("§7(TEAM) " . $event->getPlayer()->getName() . " >>> " . $message);
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @param Player $player
	 */
	public function AddDeathCount(Player $player)
	{
		if ($player->getLevel()->getName() === $this->fieldname) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('COREPVP');
			$data['death'] += 1;
			$datafile->write('COREPVP', $data);
		}
	}

	/**
	 * @param Player $player
	 */
	public function AddKillCount(Player $player)
	{
		if ($player->getLevel()->getName() === $this->fieldname) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('COREPVP');
			$data['kill'] += 1;
			$datafile->write('COREPVP', $data);
			$rand = mt_rand(1, 50);
			$this->money->addMoney($player->getName(), $rand);
			$player->sendMessage("§a+$rand §6V§bN§eCoin");
			$this->level->LevelSystem($player);
			$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
		}
	}

	/**
	 * @param Player $player
	 */
	public function AddWinCount(Player $player)
	{
		if ($player->getLevel()->getName() === $this->fieldname) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('COREPVP');
			$data['win'] += 1;
			$datafile->write('COREPVP', $data);
		}
	}

	/**
	 * @param Player $player
	 */
	public function AddLoseCount(Player $player)
	{
		if ($player->getLevel()->getName() === $this->fieldname) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('COREPVP');
			$data['lose'] += 1;
			$datafile->write('COREPVP', $data);
		}
	}

	/**
	 * @param Player $player
	 */
	public function AddBreakCoreCount(Player $player)
	{
		if ($player->getLevel()->getName() === $this->fieldname) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('COREPVP');
			$data['breakcore'] += 1;
			$datafile->write('COREPVP', $data);
		}
	}

	/**
	 * @param EntityDamageEvent $event
	 */
	public function Damage(EntityDamageEvent $event)
	{
		$entity = $event->getEntity();
		if ($entity->getLevel()->getName() === $this->fieldname) {
			if ($event instanceof EntityDamageByEntityEvent and $entity instanceof Player) {
				$damager = $event->getDamager();
				if ($damager instanceof Player) {
					if (isset($this->team[$damager->getName()])) {
						if ($this->team[$damager->getName()] === $this->team[$entity->getName()]) {
							$event->setCancelled(true);
						}
					}
				}
			}
		}
	}

	public function SendAttackMessage(string $team, string $name)
	{
		foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
			if (isset($this->team[$player->getName()])) {
				$soundpacket = new PlaySoundPacket();
				$soundpacket->soundName = 'music.breakcore';
				$soundpacket->volume = 1;
				$soundpacket->pitch = mt_rand(1, 3);
				$soundpacket->x = $player->getX();
				$soundpacket->y = $player->getY();
				$soundpacket->z = $player->getZ();
				$this->plugin->getServer()->broadcastPacket($this->plugin->getServer()->getLevelByName($this->fieldname)->getPlayers(), $soundpacket);
				switch ($team) {
					case 'Red':
						$player->addTitle("", "§cRed§eの§aコア§eが§c攻撃§eされています。", 20, 60, 20);
						$player->sendTip("§c攻撃者: §9$name\n§e残り§aHP: §c" . $this->getHP(1) . "§7/§a100");
						break;
					case 'Blue':
						$player->addTitle("", "§9Blue§eの§aコア§eが§c攻撃§eされています。", 20, 60, 20);
						$player->sendTip("§c攻撃者: §c$name\n§e残り§aHP: §c" . $this->getHP(2) . "§7/§a100");
						break;
				}
			}
		}
	}

	/**
	 * @param BlockBreakEvent $event
	 */
	public function BreakCore(BlockBreakEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$red = $this->point["red.core"];
		$blue = $this->point["blue.core"];
		if ($player->getLevel()->getName() === $this->fieldname) {
			if ($this->getGameMode()) {
				if ($block->getX() === $red["x"] && $block->getY() === $red["y"] && $block->getZ() === $red["z"]) {
					if ($this->team[$player->getName()] === "Blue") {
						if ($this->redcount >= 2 && $this->bluecount >= 2) {
							$event->setCancelled(true);
							$this->redhp--;
							$this->money->addMoney($player->getName(), 10);
							$this->AddBreakCoreCount($player);
							$player->sendMessage("§a+10 §6V§bN§eCoin");
							$this->level->LevelSystem($player);
							$this->SendAttackMessage("Red", $player->getName());
							$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
							if ($this->redhp <= 0) {
								$this->EndGame("Blue");
							}
						} else {
							$event->setCancelled(true);
							$player->sendMessage("§cプレイヤーが足りない為コアを削る事は出来ません。");
						}
					} else {
						$event->setCancelled(true);
						$player->sendMessage("§c痛い痛い！！ちょっとこれ味方のコアだよ！！");
					}
				} elseif ($block->getX() === $blue["x"] && $block->getY() === $blue["y"] && $block->getZ() === $blue["z"]) {
					if ($this->team[$player->getName()] === "Red") {
						if ($this->bluecount >= 2 && $this->redcount >= 2) {
							$event->setCancelled(true);
							$this->bluehp--;
							$this->money->addMoney($player->getName(), 10);
							$this->AddBreakCoreCount($player);
							$player->sendMessage("§a+10 §6V§bN§eCoin");
							$this->level->LevelSystem($player);
							$this->SendAttackMessage("Blue", $player->getName());
							$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
							if ($this->bluehp <= 0) {
								$this->EndGame("Red");
							}
						} else {
							$event->setCancelled(true);
							$player->sendMessage("§cプレイヤーが足りない為コアを削る事は出来ません。");
						}
					} else {
						$event->setCancelled(true);
						$player->sendMessage("§c痛い痛い！！ちょっとこれ味方のコアだよ！！");
					}
				}
			} else {
				$player->sendMessage("§cゲームモードがfalseだよ");
				$event->setCancelled(true);
			}
		}
	}

	/**
	 * @param string $team
	 */
	public function EndGame(string $team)
	{
		foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
			if ($player->getLevel()->getName() === $this->fieldname) {
				if (isset($this->team[$player->getName()])) {
					if ($this->team[$player->getName()] === $team) {
						$this->money->addMoney($player->getName(), 3000);
						$this->AddWinCount($player);
						$player->sendMessage("§7[§bSpeed§aCore§cPvP§7] おめでとうございます。あなたのチームが勝利しました。\n§7[§bSpeed§aCore§cPvP§7] §63000§6V§bN§eCoin増えました。");
					} else {
						$this->money->addMoney($player->getName(), 500);
						$this->AddLoseCount($player);
						$player->sendMessage("§7[§bSpeed§aCore§cPvP§7] 残念...あなたのチームは敗北しました。\n§7[§bSpeed§aCore§cPvP§7] §6500§6V§bN§eCoin増えました。");
					}
				}
			}
			$player->getArmorInventory()->clearAll(true);
			$player->getInventory()->clearAll(true);
			$player->removeAllEffects();
			$player->setMaxHealth(20);
			$player->setHealth(20);
			$player->setFood(20);
			$player->setSpawn(new Position(257, 8, 257, $this->plugin->getServer()->getLevelByName("lobby")));
			$player->teleport(new Position(257, 8, 257, $this->plugin->getServer()->getLevelByName("lobby")));
			$this->plugin->getScheduler()->scheduleDelayedTask(new RemoveArmorTask($this->plugin, $player), 20);
		}
		unset($this->team);
		$this->setHP(1, 100);
		$this->setHP(2, 100);
		$this->SetPlayerCount(1, 0);
		$this->SetPlayerCount(2, 0);
		$this->setGameMode(false);
		$level = $this->plugin->getServer()->getLevelByName($this->fieldname);
		$this->plugin->getServer()->unloadLevel($level);
		$this->plugin->getServer()->loadLevel($this->fieldname);
	}
}
