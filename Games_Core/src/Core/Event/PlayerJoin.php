<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 11:49
 */

namespace Core\Event;

use Core\Entity\Bossbar;
use Core\Main;
use Core\Player\Level;
use Core\Player\Rank;
use Core\Player\Tag;
use Core\Task\JoinTitle;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoin
{
	protected $plugin;
	protected $level;
	protected $rank;
	protected $tag;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->level = new Level();
		$this->rank = new Rank($this->plugin);
		$this->tag = new Tag();
	}

	public function event(PlayerJoinEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$level = $this->level->getLevel($name);
		$rank = $this->rank->getRank($name);
		$tag = $this->tag->getTag($player);
		$event->setJoinMessage("§b[§a参加§b] §7$name が参加しました。");
		$bossbar = new Bossbar();
		$bossbar->sendBar($player);
		$player->setNameTag("§7[§r $rank §7] §r$name");
		$player->setDisplayName("§7[§r $rank §7][ §rLv.$level §7][§r $tag §7] §r$name");
		$this->plugin->getScheduler()->scheduleDelayedTask(new JoinTitle($this->plugin, $player), 100);
	}
}
