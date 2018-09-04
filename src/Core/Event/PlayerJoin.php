<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 11:49
 */

namespace Core\Event;

use Core\Entity\Bossbar;
use Core\Entity\GameMaster;
use Core\Entity\Mazai;
use Core\Entity\MazaiMaster;
use Core\Main;
use Core\Player\Level;
use Core\Player\Rank;
use Core\Player\Tag;
use Core\Task\JoinTitle;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;

class PlayerJoin implements Listener
{
	private $plugin;
	private $level;
	private $rank;
	private $tag;
	private $mazaimaster;
	private $gamemaster;
	private $mazai;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->level = new Level();
		$this->rank = new Rank($this->plugin);
		$this->tag = new Tag();
		$this->mazaimaster = new MazaiMaster();
		$this->gamemaster = new GameMaster($plugin);
		$this->mazai = new Mazai();
	}

	public function event(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$level = $this->level->getLevel($name);
		$rank = $this->rank->getRank($name);
		$tag = $this->tag->getTag($player);
		$event->setJoinMessage("§b[§a参加§b] §7$name が参加しました。");
		$bossbar = new Bossbar();
		$bossbar->sendBar($player, true, 16752128);
		$player->setNameTag("§7[§r $rank §7] §r$name");
		$player->setDisplayName("§7[§r $rank §7][ §rLv.$level §7][§r $tag §7] §r$name");
		$this->mazai->Create($player, "§a魔剤§e売りの§a魔剤§eさん", "MazaiNPC", new Vector3(260, 4, 265), Item::get(Item::POTION, 11, 1), Mazai::ENTITY_ID);
		$this->mazaimaster->Create($player, "§a魔剤§7マスター", "MazaiNPC", new Vector3(287, 10, 270), Item::get(Item::POTION, 11, 1), MazaiMaster::ENTITY_ID, 100, 100);
		$this->gamemaster->Create($player, "§aGame§7Master", "GameMaster", new Vector3(252, 4, 265), Item::get(Item::COMPASS, 0, 1), GameMaster::ENTITY_ID);
		$this->plugin->getScheduler()->scheduleDelayedTask(new JoinTitle($this->plugin, $player), 100);
	}
}
