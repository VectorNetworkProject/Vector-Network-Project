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
use pocketmine\entity\Entity;
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
	protected $bluehp = 50;
	protected $redhp = 50;
	protected $bluecount = 0;
	protected $redcount = 0;
	protected $team = [];
	protected $gamemode = false;
	protected $money;
	protected $level;
	protected $fieldname = "corepvp";

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
	public function CheckGame(): bool
	{
		if ($this->gamemode) {
			return true;
		} else {
			return false;
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
	 * @param Player $player
	 */
	public function SCRespawn(Player $player)
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
		$this->AddDeathCount($player);
		$player->addTitle("§cYou are dead", "§cあなたは死んでしまった", 20, 40, 20);
	}

	public function AddDeathCount(Player $player)
	{
		if ($player->getLevel()->getName() === $this->fieldname) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('COREPVP');
			$data['death'] += 1;
			$datafile->write('COREPVP', $data);
		}
	}

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

	public function Damage(EntityDamageEvent $event)
	{
		//思考中
		//味方同士の攻撃を無効化したい
	}

	/**
	 * @param string $team
	 */
	public function EndGame(string $team)
	{
		foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
			if ($this->team[$player->getName()] === $team) {
				$this->money->addMoney($player->getName(), 3000);
				$player->sendMessage("§7[§bSpeed§aCore§cPvP§7] おめでとうございます。あなたのチームが勝利しました。\n§7[§bSpeed§aCore§cPvP§7] §63000§6V§bN§eCoin増えました。");
			} else {
				$this->money->addMoney($player->getName(), 500);
				$player->sendMessage("§7[§bSpeed§aCore§cPvP§7] 残念...あなたのチームは敗北しました。\n§7[§bSpeed§aCore§cPvP§7] §6500§6V§bN§eCoin増えました。");
			}
		}
		unset($this->team);
		$this->setHP(1, 50);
		$this->setHP(2, 50);
		$this->bluecount = 0;
		$this->redcount = 0;
	}
}