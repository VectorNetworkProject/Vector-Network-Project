<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/26
 * Time: 9:28
 */

namespace Core\Event;


use Core\Main;
use Core\Player\Level;
use Core\Player\Rank;
use Core\Player\Tag;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

class PlayerCommandPreprocess
{
	protected $plugin;
	protected $rank;
	protected $level;
	protected $tag;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->rank = new Rank($this->plugin);
		$this->level = new Level();
		$this->tag = new Tag();
	}

	public function event(PlayerCommandPreprocessEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$level = $this->level->getLevel($name);
		$rank = $this->rank->getRank($name);
		$tag = $this->tag->getTag($player);
		$player->setDisplayName("§7[§r $rank §7][ §rLv.$level §7][§r $tag §7] §r$name");
	}
}