<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/31
 * Time: 12:43
 */

namespace Core\Game\Survival;


use Core\DataFile;
use Core\Main;
use Core\Player\Level;
use Core\Player\Money;
use Core\Task\LevelCheckingTask;
use Core\Task\Teleport\TeleportSurvivalSpawnTask;
use pocketmine\block\Block;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\tile\Sign;

class SurvivalCore
{
	const LEVEL_NAME = "Survival";
	protected $plugin, $money, $level;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->money = new Money();
		$this->level = new Level();
	}

	/**
	 * @param Player $player
	 */
	public function Kit(Player $player)
	{
		$player->getInventory()->addItem(Item::get(Item::STEAK, 0, 64));
	}

	/**
	 * @param PlayerInteractEvent $event
	 */
	public function Join(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			$tile = $block->getLevel()->getTile($block);
			if ($tile instanceof Sign) {
				$text = $tile->getText();
				if ($text[0] === "§7[§2Survival §eJoin§7]") {
					$player->teleport(new Position(mt_rand(1, 999), 300, mt_rand(1, 999), $this->plugin->getServer()->getLevelByName(self::LEVEL_NAME)));
					$player->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 10 * 20, 256, false));
					$player->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 15 * 20, 256, false));
					$this->Kit($player);
					$player->addTitle("§aTeleport", "§eサバイバルの世界へ転送されました。", 40, 60, 40);
				}
			}
		}
	}

	/**
	 * @param SignChangeEvent $event
	 */
	public function Sign(SignChangeEvent $event)
	{
		$player = $event->getPlayer();
		if ($event->getLine(0) === "S1") {
			if ($player->isOp()) {
				$event->setLine(0, "§7[§2Survival §eJoin§7]");
				$event->setLine(1, "§7この看板をタッチしてサバイバルに入る");
				$event->setLine(2, "§cクソ重いかもしれません。");
			}
		}
	}

	/**
	 * @param Player $player
	 */
	public function SaveInventory(Player $player)
	{
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('SURVIVAL');
			if (isset($data['items'])) {
				if (empty($player->getInventory()->getContents())) {
					$data['items'] = array();
					$datafile->write('SURVIVAL', $data);
				} else {
					$data['items'] = $player->getInventory()->getContents();
					$datafile->write('SURVIVAL', $data);
				}
			}
		}
	}

	/**
	 * @param EntityLevelChangeEvent $event
	 */
	public function LoadData(EntityLevelChangeEvent $event)
	{
		$entity = $event->getEntity();
		if ($entity instanceof Player) {
			if ($event->getTarget()->getName() === self::LEVEL_NAME) {
				$datafile = new DataFile($entity->getName());
				$data = $datafile->get('SURVIVAL');
				if (isset($data['items'])) {
					$items = $data['items'];
					foreach ($items as $item) {
						if (isset($item['damage'])) {
							$damage = $item['damage'];
						} else {
							$damage = 0;
						}
						if (isset($item['count'])) {
							$count = $item['count'];
						} else {
							$count = 1;
						}
						$entity->getInventory()->addItem(Item::get($item['id'], $damage, $count));
					}
				}
				$entity->setHealth(self::getHealth($entity->getName()));
				$entity->setFood(self::getFood($entity->getName()));
				$this->plugin->getServer()->getLevelByName(self::LEVEL_NAME)->loadChunk($data['x'], $data['z']);
				$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportSurvivalSpawnTask($this->plugin, $entity, $data['x'], $data['y'], $data['z']), 3 * 20);
			} elseif ($event->getOrigin()->getName() === self::LEVEL_NAME) {
				$this->SaveInventory($entity);
				$this->SaveSpawn($entity->getName(), $entity->getLevel()->getName(), $entity->getX(), $entity->getY(), $entity->getZ());
				$this->SaveFood($entity);
				$this->SaveHeath($entity);
			}
		}
	}

	public function SaveData(PlayerQuitEvent $event)
	{
		$player = $event->getPlayer();
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			self::SaveFood($player);
			self::SaveHeath($player);
			self::SaveInventory($player);
			self::SaveSpawn($player->getName(), self::LEVEL_NAME, $player->getX(), $player->getY(), $player->getZ());
		}
	}

	/**
	 * @param Player $player
	 */
	public function SaveHeath(Player $player)
	{
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('SURVIVAL');
			$data['health'] = $player->getHealth();
			$datafile->write('SURVIVAL', $data);
		}
	}

	/**
	 * @param Player $player
	 */
	public function SaveFood(Player $player)
	{
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('SURVIVAL');
			$data['food'] = $player->getFood();
			$datafile->write('SURVIVAL', $data);
		}
	}

	/**
	 * @param string $name
	 * @param string $level
	 * @param float $x
	 * @param float $y
	 * @param float $z
	 */
	public function SaveSpawn(string $name, string $level, float $x, float $y, float $z)
	{
		if ($level === self::LEVEL_NAME) {
			$datafile = new DataFile($name);
			$data = $datafile->get('SURVIVAL');
			$data['x'] = $x;
			$data['y'] = $y;
			$data['z'] = $z;
			$datafile->write('SURVIVAL', $data);
		}
	}

	/**
	 * @param BlockBreakEvent $event
	 */
	public function BreakBlock(BlockBreakEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('SURVIVAL');
			switch ($block->getId()) {
				case Block::DIAMOND_ORE:
					$data['breakdiamond'] += 1;
					$money = mt_rand(100, 300);
					$exp = mt_rand(50, 100);
					$this->money->addMoney($player->getName(), $money);
					$this->level->addExp($player->getName(), $exp);
					$datafile->write('SURVIVAL', $data);
					$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
					$player->sendMessage("§a+$money §6V§bN§eCoin\n§a+$exp EXP");
					break;
				case Block::IRON_ORE:
					$data['breakiron'] += 1;
					$money = mt_rand(30, 60);
					$exp = mt_rand(20, 30);
					$this->money->addMoney($player->getName(), $money);
					$this->level->addExp($player->getName(), $exp);
					$event->setDrops([Item::get(Item::IRON_INGOT, 0, 1)]);
					$datafile->write('SURVIVAL', $data);
					$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
					$player->sendMessage("§a+$money §6V§bN§eCoin\n§a+$exp EXP");
					break;
				case Block::GOLD_ORE:
					$data['breakgold'] += 1;
					$money = mt_rand(40, 70);
					$exp = mt_rand(30, 50);
					$this->money->addMoney($player->getName(), $money);
					$this->level->addExp($player->getName(), $exp);
					$datafile->write('SURVIVAL', $data);
					$event->setDrops([Item::get(Item::GOLD_INGOT, 0, 1)]);
					$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
					$player->sendMessage("§a+$money §6V§bN§eCoin\n§a+$exp EXP");
					break;
				case Block::COAL_ORE:
					$data['breakcoal'] += 1;
					$money = mt_rand(1, 10);
					$exp = mt_rand(1, 10);
					$this->money->addMoney($player->getName(), $money);
					$this->level->addExp($player->getName(), $exp);
					$datafile->write('SURVIVAL', $data);
					$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
					$player->sendMessage("§a+$money §6V§bN§eCoin\n§a+$exp EXP");
					break;
			}
		}
	}

	/**
	 * @param Player $player
	 */
	public function AddKillCount(Player $player)
	{
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('SURVIVAL');
			$data['kill'] += 1;
			$datafile->write('SURVIVAL', $data);
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
	public function AddDeathCount(Player $player)
	{
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('SURVIVAL');
			$data['death'] += 1;
			$data['items'] = [];
			$datafile->write('SURVIVAL', $data);
			$this->SaveSpawn($player->getName(), $player->getLevel()->getName(), 225, 243, 256);
			$player->addTitle("§cYou are dead", "§cあなたは死んでしまった", 20, 40, 20);
		}
	}

	/**
	 * @param Player $player
	 */
	public function AddBreakCount(Player $player)
	{
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('SURVIVAL');
			$data['breakblock'] += 1;
			$datafile->write('SURVIVAL', $data);
		}
	}

	/**
	 * @param Player $player
	 */
	public function AddPlaceCount(Player $player)
	{
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('SURVIVAL');
			$data['placeblock'] += 1;
			$datafile->write('SURVIVAL', $data);
		}
	}

	/**
	 * @param string $name
	 * @return int
	 */
	public static function getPlaceCount(string $name): int
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('SURVIVAL');
		return $data['placeblock'];
	}

	/**
	 * @param string $name
	 * @return int
	 */
	public static function getBreakBlock(string $name): int
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('SURVIVAL');
		return $data['breakblock'];
	}

	/**
	 * @param string $name
	 * @return int
	 */
	public static function getDeathCount(string $name): int
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('SURVIVAL');
		return $data['death'];
	}

	/**
	 * @param string $name
	 * @return int
	 */
	public static function getKillCount(string $name): int
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('SURVIVAL');
		return $data['kill'];
	}

	/**
	 * @param string $name
	 * @return int
	 */
	public static function getBreakDiamond(string $name): int
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('SURVIVAL');
		return $data['breakdiamond'];
	}

	/**
	 * @param string $name
	 * @return int
	 */
	public static function getBreakIron(string $name): int
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('SURVIVAL');
		return $data['breakiron'];
	}

	/**
	 * @param string $name
	 * @return int
	 */
	public static function getBreakGold(string $name): int
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('SURVIVAL');
		return $data['breakgold'];
	}

	/**
	 * @param string $name
	 * @return int
	 */
	public static function getBreakCoal(string $name): int
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('SURVIVAL');
		return $data['breakcoal'];
	}

	/**
	 * @param string $name
	 * @return float
	 */
	public static function getHealth(string $name): float
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('SURVIVAL');
		return $data['health'];
	}

	/**
	 * @param string $name
	 * @return float
	 */
	public static function getFood(string $name): float
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('SURVIVAL');
		return $data['health'];
	}
}