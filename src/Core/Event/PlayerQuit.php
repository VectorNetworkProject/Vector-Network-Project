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
use Core\Entity\GameMaster;
use Core\Entity\Mazai;
use Core\Entity\MazaiMaster;
use Core\Game\Duel\DuelCore;
use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Game\Survival\SurvivalCore;
use Core\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerQuit implements Listener
{
	private $speedcorepvp, $duel, $survival, $mazai, $mazaimaster, $gamemaster;

	public function __construct(Main $plugin)
	{
		$this->speedcorepvp = new SpeedCorePvPCore($plugin);
		$this->duel = new DuelCore();
		$this->survival = new SurvivalCore($plugin);
		$this->mazai = new Mazai();
		$this->mazaimaster = new MazaiMaster();
		$this->gamemaster = new GameMaster($plugin);
	}

	public function event(PlayerQuitEvent $event): void
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		//$event->setQuitMessage("§b[§c退出§b] §7$name が退出しました。");
		$event->setQuitMessage(null);
		$this->speedcorepvp->GameQuit($player);
		$this->survival->SaveData($player);
		$this->mazai->Remove($player, Mazai::ENTITY_ID);
		$this->gamemaster->Remove($player, GameMaster::ENTITY_ID);
		$this->mazaimaster->Remove($player, MazaiMaster::ENTITY_ID);
		$this->duel->Quit($event);
		$bossbar = new Bossbar();
		$bossbar->RemoveBar($player);
		$data = new DataFile($player->getName());
		$user = $data->get("USERDATA");
		$user["lastlogin"] = date("Y年m月d日 H時i分s秒");
		$data->write("USERDATA", $user);
	}
}
