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
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\tile\Sign;

class SurvivalCore
{
	const LEVEL_NAME = "Survival";
	protected $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function Kit(Player $player)
	{

	}

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
				}
			}
		}
	}

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

	public function SaveInventory(Player $player)
	{
		if ($player->getLevel()->getName() === self::LEVEL_NAME) {
			$datafile = new DataFile($player->getName());
			$data = $datafile->get('SURVIVAL_INVENTORY');
			if (isset($data['items'])) {
				$data['items'] = $player->getInventory()->getContents();
				$datafile->write('SURVIVAL_INVENTORY', $data);
			}
		}
	}

	public function LoadInventory(EntityLevelChangeEvent $event)
	{
		$entity = $event->getEntity();
		if ($entity instanceof Player) {
			if ($event->getTarget()->getName() === self::LEVEL_NAME) {
				$datafile = new DataFile($entity->getName());
				$data = $datafile->get('SURVIVAL_INVENTORY');
				if (isset($data['items'])) {
					$items = $data['items'];
					foreach ($items as $item) {
						if (isset($item['damage'])) {
							$damage = $item['damage'];
						} else {
							$damage = 0;
						}
						$entity->getInventory()->addItem(Item::get($item['id'], $damage, $item['count']));
					}
				}
			} elseif ($event->getOrigin()->getName() === self::LEVEL_NAME) {
				$this->SaveInventory($entity);
			}
		}
	}
}