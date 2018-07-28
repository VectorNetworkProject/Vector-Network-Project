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
use Core\Task\LevelCheckingTask;
use pocketmine\block\Block;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Armor;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\Color;

class SpeedCorePvPCore
{
	protected $plugin;
	protected $bluecolor;
	protected $redcolor;
	protected $bluehp = 75;
	protected $redhp = 75;
	protected $bluecount = 0;
	protected $redcount = 0;
	protected $team = [];
	protected $gamemode = false;
	protected $money;
	protected $level;
	protected $fieldname = "corepvp";
	protected $point = [
		"blue.core" => [
			"x" => 0,
			"y" => 0,
			"z" => 0
		],
		"blue.spawn" => [
			"x" => 0,
			"y" => 0,
			"z" => 0
		],
		"red.core" => [
			"x" => 0,
			"y" => 0,
			"z" => 0
		],
		"red.spawn" => [
			"x" => 0,
			"y" => 0,
			"z" => 0
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

	/**
	 * @return bool
	 */
	public function getGameMode(): bool
	{
		if ($this->gamemode) {
			return true;
		} else {
			return false;
		}
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
	public function getHP(int $teamid)
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
			"gold_pickaxe" => Item::get(Item::GOLD_PICKAXE, 0, 1)
		];
		if ($this->team[$player->getName()] === "Red") {
			if ($armors instanceof Durable and $armors instanceof Armor) {
				$armors->setUnbreakable(true);
				$armors->setCustomColor($this->redcolor);
			}
		} else {
			if ($armors instanceof Durable and $armors instanceof Armor) {
				$armors->setUnbreakable(true);
				$armors->setCustomColor($this->bluecolor);
			}
		}
		if ($weapons instanceof Durable) {
			$weapons->setUnbreakable(true);
		}
		$armor = $player->getArmorInventory();
		$armor->setHelmet($armors['leather_cap']);
		$armor->setChestplate($armors['leather_tunic']);
		$armor->setLeggings($armors['leather_pants']);
		$armor->setBoots($armors['leather_boots']);
		$player->getInventory()->addItem($weapons['stone_sword']);
		$player->getInventory()->addItem($weapons['bow']);
		$player->getInventory()->addItem($weapons['gold_pickaxe']);
		$player->getInventory()->addItem(Item::get(Item::BREAD, 0, 64));
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
				if ($this->team[$entity->getName()] !== false) {
					$damager = $event->getDamager();
					if ($damager instanceof Player) {
						if ($this->team[$damager->getName()] === $this->team[$entity->getName()]) {
							$event->setCancelled(true);
						}
					}
				}
			}
		}
	}

	/**
	 * @param Player $player
	 * @param Block $block
	 */
	public function BreakCore(Player $player, Block $block)
	{
		if ($this->getGameMode()) {
			$red = $this->point["red.core"];
			$blue = $this->point["blue.core"];
			if ($player->getLevel()->getName() === $this->fieldname) {
				if ($block->getX() === $red["x"] && $block->getY() === $red["y"] && $block->getZ() === $red["z"]) {
					if ($this->team[$player->getName()] === "Blue") {
						if ($this->redcount >= 3 && $this->bluecount >= 3) {
							$this->redhp--;
							$this->money->addMoney($player->getName(), 10);
							$this->AddBreakCoreCount($player);
							$player->sendMessage("§a+10 §6V§bN§eCoin");
							$this->level->LevelSystem($player);
							$this->plugin->getServer()->broadcastPopup("§cRed §6のコアが削られています。");
							$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
						} else {
							$player->sendMessage("§cプレイヤーが足りない為コアを削る事は出来ません。");
						}
					}
				} elseif ($block->getX() === $blue["x"] && $block->getY() === $blue["y"] && $block->getZ() === $blue["z"]) {
					if ($this->team[$player->getName()] === "Red") {
						if ($this->bluecount >= 3 && $this->redcount >= 3) {
							$this->bluehp--;
							$this->money->addMoney($player->getName(), 10);
							$this->AddBreakCoreCount($player);
							$player->sendMessage("§a+10 §6V§bN§eCoin");
							$this->level->LevelSystem($player);
							$this->plugin->getServer()->broadcastPopup("§9Blue §6のコアが削られています。");
							$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
						} else {
							$player->sendMessage("§cプレイヤーが足りない為コアを削る事は出来ません。");
						}
					}
				}
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
				if ($this->team[$player->getName()] === $team) {
					$this->money->addMoney($player->getName(), 3000);
					$player->sendMessage("§7[§bSpeed§aCore§cPvP§7] おめでとうございます。あなたのチームが勝利しました。\n§7[§bSpeed§aCore§cPvP§7] §63000§6V§bN§eCoin増えました。");
				} else {
					$this->money->addMoney($player->getName(), 500);
					$player->sendMessage("§7[§bSpeed§aCore§cPvP§7] 残念...あなたのチームは敗北しました。\n§7[§bSpeed§aCore§cPvP§7] §6500§6V§bN§eCoin増えました。");
				}
			}
		}
		unset($this->team);
		$this->setHP(1, 75);
		$this->setHP(2, 75);
		$this->SetPlayerCount(1, 0);
		$this->SetPlayerCount(2, 0);
		$this->setGameMode(false);
	}
}