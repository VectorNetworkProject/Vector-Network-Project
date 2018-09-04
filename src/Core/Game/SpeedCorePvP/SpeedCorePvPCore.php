<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/24
 * Time: 22:36
 */

namespace Core\Game\SpeedCorePvP;

use Core\Commands\MessagesEnum;
use Core\DataFile;
use Core\Main;
use Core\Player\Level;
use Core\Player\Money;
use Core\Task\AutosetBlockTask;
use Core\Task\LevelCheckingTask;
use Core\Task\RemoveArmorTask;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityInventoryChangeEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Armor;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\level\particle\HugeExplodeSeedParticle;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\tile\Sign;
use pocketmine\utils\Color;
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\FormApi;

class SpeedCorePvPCore
{
	/** @var int */
	public const TEAM_RED = 1;
	public const TEAM_BLUE = 2;

	/** @var SpeedCorePvPCore */
	private static $instance;
	/** @var int[] */
	private static $blockids = [
		Block::IRON_ORE => 20,
		Block::GOLD_ORE => 20,
		Block::COAL_ORE => 15,
		Block::DIAMOND_ORE => 60,
		Block::LOG => 10,
		Block::MELON_BLOCK => 10
	];

	/** @var Main */
	private $plugin;
	/** @var Color */
	private $redColor;
	/** @var Color */
	private $blueColor;
	/** @var int */
	private $redHp = 300;
	/** @var int */
	private $blueHp = 300;
	/** @var int */
	private $redCount = 0;
	/** @var int */
	private $blueCount = 0;
	/** @var array */
	private $team = [];
	/** @var bool */
	private $gamemode = false;
	/** @var Money */
	private $money;
	/** @var Level */
	private $level;
	/** @var string */
	private $fieldName = "corepvp";
	/** @var array */
	private $point = [
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
		self::$instance = $this;
		$this->plugin = $plugin;
		$this->redColor = new Color(255, 0, 0);
		$this->blueColor = new Color(0, 0, 255);
		$this->money = new Money();
		$this->level = new Level();
	}

	/**
	 * @return bool
	 */
	public function getGameMode(): bool
	{
		return $this->gamemode ? true : false;
	}

	/**
	 * @param bool $bool
	 * @return SpeedCorePvPCore
	 */
	public function setGameMode(bool $bool): self
	{
		$this->gamemode = $bool;
		return $this;
	}

	/**
	 * @param int $teamId
	 * @param int $hp
	 * @return SpeedCorePvPCore
	 */
	public function setHP(int $teamId, int $hp): self
	{
		switch ($teamId) {
			case self::TEAM_RED:
				$this->redHp = $hp;
				break;
			case self::TEAM_BLUE:
				$this->blueHp = $hp;
				break;
			default:
				throw new \InvalidArgumentException("teamIDは1か2のみです");
				break;
		}
		return $this;
	}

	/**
	 * @param int $teamId
	 * @return int
	 */
	public function getHP(int $teamId = self::TEAM_RED): int
	{
		switch ($teamId) {
			case self::TEAM_RED:
				return $this->redHp;
				break;
			case self::TEAM_BLUE:
				return $this->blueHp;
				break;
			default:
				throw new \InvalidArgumentException("teamIDは1か2のみです");
				break;
		}
	}

	/**
	 * @param int $teamId
	 * @param int $count
	 * @return SpeedCorePvPCore
	 */
	public function setPlayerCount(int $teamId, int $count): self
	{
		switch ($teamId) {
			case self::TEAM_RED:
				$this->redCount = $count;
				break;
			case self::TEAM_BLUE:
				$this->blueCount = $count;
				break;
			default:
				throw new \InvalidArgumentException("teamIDは1か2のみです");
				break;
		}
		return $this;
	}

	/**
	 * @param int $teamId
	 * @return SpeedCorePvPCore
	 */
	public function AddPlayerCount(int $teamId = self::TEAM_RED): self
	{
		switch ($teamId) {
			case self::TEAM_RED:
				++$this->redCount;
				break;
			case self::TEAM_BLUE:
				++$this->blueCount;
				break;
			default:
				throw new \InvalidArgumentException("teamIDは1か2のみです");
				break;
		}
		return $this;
	}

	/**
	 * @param int $teamId
	 * @return int
	 */
	public function getPlayerCount(int $teamId = self::TEAM_RED): int
	{
		switch ($teamId) {
			case self::TEAM_RED:
				return $this->redCount;
				break;
			case self::TEAM_BLUE:
				return $this->blueCount;
				break;
			default:
				throw new \InvalidArgumentException("teamIDは1か2のみです");
		}
	}

	/**
	 * @param Player $player
	 * @return SpeedCorePvPCore
	 */
	public function setSpawn(Player $player): self
	{
		// TODO: レベルをインスタンスにコンストラクタで渡しておくことでエラー回避
		$level = Server::getInstance()->getLevelByName($this->fieldName);
		$red = $this->point["red.spawn"];
		$blue = $this->point["blue.spawn"];
		if ($this->team[$player->getName()] === "Red") {
			$player->setSpawn(new Position($red["x"], $red["y"], $red["z"], $level));
			$player->teleport(new Position($red["x"], $red["y"], $red["z"], $level));
		} else {
			$player->setSpawn(new Position($blue["x"], $blue["y"], $blue["z"], $level));
			$player->teleport(new Position($blue["x"], $blue["y"], $blue["z"], $level));
		}
		return $this;
	}

	/**
	 * @param Player $player
	 * @param Block $block
	 * @return SpeedCorePvPCore
	 */
	public function GameJoin(Player $player, Block $block): self
	{
		if ($player->getLevel()->getName() === $this->fieldName) {
			if ($block->getId() === Block::EMERALD_BLOCK) {
				$this->setGameMode(true);
				if (isset($this->team[$player->getName()])) {
					$player->sendMessage("§cあなたは既にチームに所属しています。");
					return $this;
				}

				if ($this->redCount < $this->blueCount) {
					$this->team[$player->getName()] = "Red";
					$this->AddPlayerCount(self::TEAM_RED);
					$player->sendMessage("§7あなたは §cRed §7Teamになりました。");
				} else {
					$this->team[$player->getName()] = "Blue";
					$this->AddPlayerCount(self::TEAM_BLUE);
					$player->sendMessage("§7あなたは §9Blue §7Teamになりました。");
				}
				$this->setSpawn($player);
				$this->Kit($player);
			}
		}
		return $this;
	}

	/**
	 * @param Player $player
	 * @return SpeedCorePvPCore
	 */
	public function GameQuit(Player $player): self
	{
		if (isset($this->team[$player->getName()])) {
			if ($this->team[$player->getName()] === "Red") {
				$this->ReducePlayerCount(self::TEAM_RED);
				$player->sendMessage("§cRed §7Teamから退出しました。");
			} elseif ($this->team[$player->getName()] === "Blue") {
				$this->ReducePlayerCount(self::TEAM_BLUE);
				$player->sendMessage("§9Blue §7Teamから退出しました。");
			}
			unset($this->team[$player->getName()]);
		}
		return $this;
	}

	/**
	 * @param BlockBreakEvent $event
	 */
	public function DropItem(BlockBreakEvent $event): void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if ($player->getLevel()->getName() === $this->fieldName) {
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
					$event->setDrops([Item::get(Item::WOODEN_PLANKS, 0, 4)]);
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

	public function CancelCraft(CraftItemEvent $event)
	{
		$player = $event->getPlayer();
		if ($player->getLevel()->getName() !== $this->fieldName) return;
		foreach ($event->getOutputs() as $item) {
			if ($item->getId() === Item::MELON_BLOCK) {
				$event->setCancelled(true);
			}
		}
	}

	public function CancelChange(EntityInventoryChangeEvent $event)
	{
		$entity = $event->getEntity();
		if (!$entity instanceof Player) return;
		if ($entity->getLevel()->getName() !== $this->fieldName) return;
		if (!isset($this->team[$entity->getName()])) return;
		if ($event->getSlot() !== 0) return;
		if ($event->getOldItem()->getId() === Item::LEATHER_HELMET) {
			$event->setCancelled(true);
		}
	}

	/**
	 * @param BlockPlaceEvent $event
	 */
	public function AntiPlace(BlockPlaceEvent $event): void
	{
		$player = $event->getPlayer();
		if (!$player->isOp()) {
			$block = $event->getBlock();
			if ($player->getName() === $this->fieldName) {
				switch ($block->getId()) {
					case Block::MELON_BLOCK:
					case Block::LOG:
					case Block::LOG2:
						$event->setCancelled(true);
						break;
				}
			}
		}
	}

	/**
	 * @param int $teamId
	 * @return SpeedCorePvPCore
	 */
	public function ReducePlayerCount(int $teamId): self
	{
		switch ($teamId) {
			case self::TEAM_RED:
				--$this->redCount;
				break;
			case self::TEAM_BLUE:
				--$this->blueCount;
				break;
			default:
				break;
		}
		return $this;
	}

	public function LevelChange(EntityLevelChangeEvent $event): void
	{
		$entity = $event->getEntity();
		if ($event->getOrigin()->getName() === $this->fieldName) {
			if ($entity instanceof Player) {
				$this->GameQuit($entity);
				$entity->getArmorInventory()->clearAll(true);
				$this->plugin->getScheduler()->scheduleDelayedTask(new RemoveArmorTask($this->plugin, $entity), 20);
			}
		}
	}

	/**
	 * @param Player $player
	 * @return SpeedCorePvPCore
	 */
	public function kit(Player $player): self
	{
		$armors = [
			"leather_cap" => Item::get(Item::LEATHER_CAP, 0, 1),
			"leather_tunic" => Item::get(Item::LEATHER_TUNIC, 0, 1),
			"leather_pants" => Item::get(Item::LEATHER_PANTS, 0, 1),
			"leather_boots" => Item::get(Item::LEATHER_BOOTS, 0, 1)
		];
		$weapons = [
			"wooden_sword" => Item::get(Item::WOODEN_SWORD, 0, 1),
			"gold_pickaxe" => Item::get(Item::GOLD_PICKAXE, 0, 1),
			"stone_axe" => Item::get(Item::STONE_AXE, 0, 1),
			"stone_shovel" => Item::get(Item::STONE_SHOVEL, 0, 1)
		];
		$this->team[$player->getName()] === "Red" ? $teamColor = $this->redColor : $teamColor = $this->blueColor;
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
		$player->getInventory()->addItem($weapons['wooden_sword']);
		$player->getInventory()->addItem($weapons['gold_pickaxe']);
		$player->getInventory()->addItem($weapons['stone_axe']);
		$player->getInventory()->addItem($weapons['stone_shovel']);
		return $this;
	}

	/**
	 * @param Player $player
	 * @return SpeedCorePvPCore
	 */
	public function respawn(Player $player): self
	{
		if ($player->getLevel()->getName() === $this->fieldName) {
			$this->kit($player);
			$player->addTitle("§cYou are dead", "§cあなたは死んでしまった", 20, 40, 20);
		}
		return $this;
	}

	public function teamChat(PlayerChatEvent $event): void
	{
		if ($event->getPlayer()->getLevel()->getName() === $this->fieldName) {
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
	 * @return SpeedCorePvPCore
	 */
	public function addDeathCount(Player $player): self
	{
		if ($player->getLevel()->getName() === $this->fieldName) {
			$datafile = new DataFile($player->getName());// TODO
			$data = $datafile->get('COREPVP');
			$data['death'] += 1;
			$datafile->write('COREPVP', $data);
		}
		return $this;
	}

	/**
	 * @param Player $player
	 * @return SpeedCorePvPCore
	 */
	public function addKillCount(Player $player): self
	{
		if ($player->getLevel()->getName() === $this->fieldName) {
			$datafile = new DataFile($player->getName());// TODO
			$data = $datafile->get('COREPVP');
			$data['kill'] += 1;
			$datafile->write('COREPVP', $data);
			$rand = mt_rand(1, 50);
			$this->money->addMoney($player->getName(), $rand);
			$player->sendMessage("§a+" . $rand . " §6V§bN§eCoin");
			$this->level->LevelSystem($player);
			$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
		}
		return $this;
	}

	/**
	 * @param Player $player
	 * @return SpeedCorePvPCore
	 */
	public function addWinCount(Player $player): self
	{
		if ($player->getLevel()->getName() === $this->fieldName) {
			$datafile = new DataFile($player->getName());// TODO
			$data = $datafile->get('COREPVP');
			$data['win'] += 1;
			$datafile->write('COREPVP', $data);
		}
		return $this;
	}

	/**
	 * @param Player $player
	 * @return SpeedCorePvPCore
	 */
	public function addLoseCount(Player $player): self
	{
		if ($player->getLevel()->getName() === $this->fieldName) {
			$datafile = new DataFile($player->getName());// TODO
			$data = $datafile->get('COREPVP');
			$data['lose'] += 1;
			$datafile->write('COREPVP', $data);
		}
		return $this;
	}

	/**
	 * @param Player $player
	 * @return SpeedCorePvPCore
	 */
	public function addBreakCoreCount(Player $player): self
	{
		if ($player->getLevel()->getName() === $this->fieldName) {
			$datafile = new DataFile($player->getName());// TODO
			$data = $datafile->get('COREPVP');
			$data['breakcore'] += 1;
			$datafile->write('COREPVP', $data);
		}
		return $this;
	}

	/**
	 * @param EntityDamageEvent $event
	 */
	public function damage(EntityDamageEvent $event): void
	{
		$entity = $event->getEntity();
		if ($entity->getLevel()->getName() === $this->fieldName) {
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

	/**
	 * @param string $team
	 * @param string $name
	 * @return SpeedCorePvPCore
	 */
	public function SendAttackMessage(string $team, string $name): self
	{
		foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
			if (isset($this->team[$player->getName()])) {
				$soundpacket = new PlaySoundPacket();
				$soundpacket->soundName = 'music.breakcore';
				$soundpacket->volume = 1;
				$soundpacket->pitch = self::Rand();
				$soundpacket->x = $player->getX();
				$soundpacket->y = $player->getY();
				$soundpacket->z = $player->getZ();
				$this->plugin->getServer()->broadcastPacket($this->plugin->getServer()->getLevelByName($this->fieldName)->getPlayers(), $soundpacket);
				switch ($team) {
					case 'Red':
						$player->addTitle("", "§cRed§eの§aコア§eが§c攻撃§eされています。", 20, 60, 20);
						$player->sendTip("§c攻撃者: §9" . $name . "\n§e残り§aHP: §c" . $this->getHP(1) . "§7/§a300");
						break;
					case 'Blue':
						$player->addTitle("", "§9Blue§eの§aコア§eが§c攻撃§eされています。", 20, 60, 20);
						$player->sendTip("§c攻撃者: §c" . $name . "\n§e残り§aHP: §c" . $this->getHP(2) . "§7/§a300");
						break;
				}
			}
		}
		return $this;
	}

	/**
	 * @return float
	 */
	public static function rand(): float
	{
		return mt_rand(8, 10) / 10;
	}

	/**
	 * @param SignChangeEvent $event
	 */
	public function Sign(SignChangeEvent $event): void
	{
		$player = $event->getPlayer();
		if (!$player->getLevel()->getName() === $this->fieldName) return;
		if (!$player->isOp()) return;
		if ($event->getLine(0) === "SCP1") {
			$event->setLine(0, "§7[§bS§aC§cP §aSHOP§7]");
			$event->setLine(1, "§7看板をタッチしてメニューを開きます");
		}
		if ($event->getLine(0) === "SCP2") {
			$event->setLine(0, "§7[§bS§aC§cP §aSTATUS§7]");
			$event->setLine(1, "§7看板をタッチしてステータスを見ます");
		}
	}

	public function Interact(PlayerInteractEvent $event): void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$tile = $block->getLevel()->getTile($block);
		if (!$player->getLevel()->getName() === $this->fieldName) return;
		if (!$tile instanceof Sign) return;
		$text = $tile->getText();
		if ($text[0] === "§7[§bS§aC§cP §aSHOP§7]") {
			FormApi::makeListForm(function (Player $player, ?int $key) {
				if (!FormApi::formCancelled($key)) {
					switch ($key) {
						case 0:
							if ($player->getInventory()->contains(Item::get(Item::GOLD_INGOT, 0, 12))) {
								$player->getInventory()->removeItem(Item::get(Item::GOLD_INGOT, 0, 12));
								$player->getInventory()->addItem(Item::get(Item::BOW, 0, 1));
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
							} else {
								$player->sendMessage("§7[§c失敗§7] §6金§cが足りません。");
							}
							break;
						case 1:
							if ($player->getInventory()->contains(Item::get(Item::GOLD_INGOT, 0, 2))) {
								$player->getInventory()->removeItem(Item::get(Item::GOLD_INGOT, 0, 2));
								$player->getInventory()->addItem(Item::get(Item::ARROW, 0, 6));
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
							} else {
								$player->sendMessage("§7[§c失敗§7] §6金§cが足りません。");
							}
							break;
						case 2:
							if ($player->getInventory()->contains(Item::get(Item::GOLD_INGOT, 0, 50))) {
								$player->getInventory()->removeItem(Item::get(Item::GOLD_INGOT, 0, 50));
								$player->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 1));
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
							} else {
								$player->sendMessage("§7[§c失敗§7] §6金§cが足りません。");
							}
							break;
						case 3:
							if ($player->getInventory()->contains(Item::get(Item::GOLD_INGOT, 0, 100))) {
								$player->getInventory()->removeItem(Item::get(Item::GOLD_INGOT, 0, 100));
								$player->getInventory()->addItem(Item::get(Item::APPLE_ENCHANTED, 0, 1));
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
							} else {
								$player->sendMessage("§7[§c失敗§7] §6金§cが足りません。");
							}
							break;
					}
				}
			})->setTitle("§bSpeed§aCore§cPvP")
				->setContent("採掘した資材を武器等に変換できます。")
				->addButton(new Button("§6弓\n§e金: §612個"))
				->addButton(new Button("§7矢6個\n§e金: §62個"))
				->addButton(new Button("§e金リンゴ\n§e金: §650個"))
				->addButton(new Button("§d上位の§e金リンゴ\n§e金: §6100個"))
				->sendToPlayer($player);
		}
		if ($text[0] === "§7[§bS§aC§cP §aSTATUS§7]") {
			$red = self::getPlayerCount(1);
			$redHp = self::getHP(1);
			$blue = self::getPlayerCount(2);
			$blueHp = self::getHP(2);
			FormApi::makeCustomForm()->setTitle("§bSpeed§aCore§cPvP")
				->addElement(new Label("---===<§c Red §r>===---\n§6人数§r: $red 人\n§aHP§r: $redHp"))
				->addElement(new Label("---===<§9 Blue §r>===---\n§6人数§r: $blue 人\n§aHP§r: $blueHp"))
				->sendToPlayer($player);
		}
	}

	/**
	 * @param BlockBreakEvent $event
	 */
	public function BreakCore(BlockBreakEvent $event): void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$red = $this->point["red.core"];
		$blue = $this->point["blue.core"];
		if ($player->getLevel()->getName() !== $this->fieldName) return;
		if (!($this->getGameMode())) {
			$player->sendMessage("§cゲームモードがfalseだよ");
			$event->setCancelled(true);
			return;
		}
		if ($block->getX() === $red["x"] && $block->getY() === $red["y"] && $block->getZ() === $red["z"]) {
			$event->setCancelled(true);
			if ($this->team[$player->getName()] !== "Blue") {
				$player->sendMessage("§c痛い痛い！！ちょっとこれ味方のコアだよ！！");
				return;
			}
			if ($this->redCount < 1 || $this->blueCount < 1) {
				$player->sendMessage("§cプレイヤーが足りない為コアを削る事は出来ません。");
				return;
			}
			--$this->redHp;
			$this->money->addMoney($player->getName(), 10);
			$this->AddBreakCoreCount($player);
			$player->sendMessage("§a+10 §6V§bN§eCoin");
			$this->level->LevelSystem($player);
			$this->SendAttackMessage("Red", $player->getName());
			$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
			Main::$instance->getServer()->getLevelByName($this->fieldName)->addParticle(new HugeExplodeSeedParticle(new Vector3($red['x'], $red['y'], $red['z'])), $this->plugin->getServer()->getLevelByName($this->fieldName)->getPlayers());
		} elseif ($block->getX() === $blue["x"] && $block->getY() === $blue["y"] && $block->getZ() === $blue["z"]) {
			$event->setCancelled(true);
			if ($this->team[$player->getName()] !== "Red") {
				$player->sendMessage("§c痛い痛い！！ちょっとこれ味方のコアだよ！！");
				return;
			}
			if ($this->blueCount < 1 || $this->redCount < 1) {
				$player->sendMessage("§cプレイヤーが足りない為コアを削る事は出来ません。");
				return;
			}
			--$this->blueHp;
			$this->money->addMoney($player->getName(), 10);
			$this->AddBreakCoreCount($player);
			$player->sendMessage("§a+10 §6V§bN§eCoin");
			$this->level->LevelSystem($player);
			$this->SendAttackMessage("Blue", $player->getName());
			$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
			Main::$instance->getServer()->getLevelByName($this->fieldName)->addParticle(new HugeExplodeSeedParticle(new Vector3($blue['x'], $blue['y'], $blue['z'])), $this->plugin->getServer()->getLevelByName($this->fieldName)->getPlayers());
		}
		if ($this->redHp <= 0) {
			$this->EndGame("Blue");
		}
		if ($this->blueHp <= 0) {
			$this->EndGame("Red");
		}
	}

	/**
	 * @param string $team
	 */
	public function EndGame(string $team)
	{
		foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
			if ($player->getLevel()->getName() === $this->fieldName) {
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
		}
		unset($this->team);
		$this->setHP(1, 300);
		$this->setHP(2, 300);
		$this->SetPlayerCount(1, 0);
		$this->SetPlayerCount(2, 0);
		$this->setGameMode(false);
		$level = $this->plugin->getServer()->getLevelByName($this->fieldName);
		$this->plugin->getServer()->unloadLevel($level);
		$this->plugin->getServer()->loadLevel($this->fieldName);
	}
}
