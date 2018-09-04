<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/09/04
 * Time: 13:29
 */

namespace Core\Event;


use Core\Entity\GameMaster;
use Core\Entity\Mazai;
use Core\Entity\MazaiMaster;
use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Game\Survival\SurvivalCore;
use Core\Main;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class EntityLevelChange implements Listener
{
	private $speedcorepvp, $survival, $gamemaster, $mazai, $mazaimaster;
	public function __construct(Main $plugin)
	{
		$this->speedcorepvp = new SpeedCorePvPCore($plugin);
		$this->survival = new SurvivalCore($plugin);
		$this->mazai = new Mazai();
		$this->mazaimaster = new MazaiMaster();
		$this->gamemaster = new GameMaster($plugin);
	}

	public function event(EntityLevelChangeEvent $event)
	{
		$entity = $event->getEntity();
		$this->speedcorepvp->LevelChange($event);
		$this->survival->LoadData($event);
		$this->gamemaster->Check($event);
		$this->mazai->Check($event);
		$this->mazaimaster->Check($event);
		if (!$entity instanceof Player) return;
		if ($entity->getLevel()->getName() !== "Survival") return;
		$this->survival->SaveData($entity);
	}
}