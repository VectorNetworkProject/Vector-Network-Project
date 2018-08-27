<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/14
 * Time: 16:58
 */

namespace Core\Game\FFAPvP;

use Core\DataFile;
use Core\Main;
use Core\Player\Level;
use Core\Player\Money;
use Core\Task\LevelCheckingTask;
use pocketmine\block\Block;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\Player;

class FFAPvPCore
{
	protected $money;
	protected $level;
	protected $plugin;
	public $worldname = "ffapvp";

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->money = new Money();
		$this->level = new Level();
	}

	public function AddDeathCount(Player $player)
	{
		if ($player->getLevel()->getName() !== $this->worldname) return;
		$datafile = new DataFile($player->getName());
		$data = $datafile->get('FFAPVP');
		$data['death'] += 1;
		$datafile->write('FFAPVP', $data);
		$player->addTitle("§cYou are dead", "§cあなたは死んでしまった", 20, 40, 20);
	}

	public function AddKillCount(Player $player)
	{
		if ($player->getLevel()->getName() !== $this->worldname) return;
		$datafile = new DataFile($player->getName());
		$data = $datafile->get('FFAPVP');
		$data['kill'] += 1;
		$datafile->write('FFAPVP', $data);
		$rand = mt_rand(1, 50);
		$this->money->addMoney($player->getName(), $rand);
		$player->sendMessage("§a+$rand §6V§bN§eCoin");
		$this->level->LevelSystem($player);
		$this->plugin->getScheduler()->scheduleDelayedTask(new LevelCheckingTask($this->plugin, $player), 20);
	}

	public function FFAPvPKit(Player $player, Block $block)
	{
		if ($player->getLevel()->getName() !== $this->worldname) return;
		if ($block->getId() !== 133) return;

		$player->getInventory()->clearAll(true);
		$player->setMaxHealth(20);
		$player->setHealth(20);
		$player->setFood(20);
		$items = [
			"leather_cap" => Item::get(Item::LEATHER_CAP, 0, 1),
			"chain_tunic" => Item::get(Item::CHAIN_CHESTPLATE, 0, 1),
			"chain_leggings" => Item::get(Item::CHAIN_LEGGINGS, 0, 1),
			"leather_boots" => Item::get(Item::LEATHER_BOOTS, 0, 1),
			"wooden_sword" => Item::get(Item::WOODEN_SWORD, 0, 1),
			"bow" => Item::get(Item::BOW, 0, 1)
		];
		foreach ($items as $item) {
			if ($item instanceof Durable) {
				$item->setUnbreakable(true);
			}
		}
		$armor = $player->getArmorInventory();
		$armor->setHelmet($items["leather_cap"]);
		$armor->setChestplate($items["chain_tunic"]);
		$armor->setLeggings($items["chain_leggings"]);
		$armor->setBoots($items["leather_boots"]);
		$player->getInventory()->addItem($items["wooden_sword"]);
		$player->getInventory()->addItem($items["bow"]);
		$player->getInventory()->addItem(Item::get(Item::STEAK, 0, 64));
		$player->getInventory()->addItem(Item::get(Item::ARROW, 0, 64));
		$player->sendMessage("§a初期装備を与えました。");
	}
}
