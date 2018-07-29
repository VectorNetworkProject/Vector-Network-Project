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
use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Main;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerQuit
{
	protected $plugin, $speedcorepvp;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->speedcorepvp = new SpeedCorePvPCore($this->plugin);
	}

	public function event(PlayerQuitEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$event->setQuitMessage("§b[§c退出§b] §7$name が退出しました。");
		$this->speedcorepvp->GameQuit($player);
		$bossbar = new Bossbar();
		$bossbar->RemoveBar($player);
		$data = new DataFile($player->getName());
		$user = $data->get("USERDATA");
		$user["lastlogin"] = date("Y年m月d日 H時i分s秒");
		$data->write("USERDATA", $user);
	}
}
