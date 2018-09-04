<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/09/04
 * Time: 11:30
 */

namespace Core\Event;


use Core\Entity\GameMaster;
use Core\Entity\Mazai;
use Core\Entity\MazaiMaster;
use Core\Main;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;

class DataPacketReceive implements Listener
{
	private $mazaimaster, $gamemaster, $mazai;
	public function __construct(Main $plugin)
	{
		$this->mazaimaster = new MazaiMaster();
		$this->gamemaster = new GameMaster($plugin);
		$this->mazai = new Mazai();
	}

	public function event(DataPacketReceiveEvent $event)
	{
		$this->mazai->ClickEntity($event);
		$this->gamemaster->ClickEntity($event);
		$this->mazaimaster->ClickEntity($event);
	}
}