<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 12:00
 */

namespace Core\Event;

use Core\DataFile;
use Core\Entity\Bossbar;
use Core\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerQuit implements Listener
{
	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function event(PlayerQuitEvent $event): void
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		//$event->setQuitMessage("§b[§c退出§b] §7$name が退出しました。");
		$event->setQuitMessage(null);
		$bossbar = new Bossbar();
		$bossbar->RemoveBar($player);
		$data = new DataFile($player->getName());
		$user = $data->get("USERDATA");
		$user["lastlogin"] = date("Y年m月d日 H時i分s秒");
		$data->write("USERDATA", $user);
	}
}
