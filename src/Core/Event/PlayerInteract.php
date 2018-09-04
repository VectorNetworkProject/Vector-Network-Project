<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/22
 * Time: 13:59
 */

namespace Core\Event;

use Core\Game\Duel\DuelCore;
use Core\Game\FFAPvP\FFAPvPCore;
use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Game\Survival\SurvivalCore;
use Core\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class PlayerInteract implements Listener
{
	private $ffapvp, $speedcorepvp, $survival, $duel;

	public function __construct(Main $plugin)
	{
		$this->ffapvp = new FFAPvPCore($plugin);
		$this->speedcorepvp = new SpeedCorePvPCore($plugin);
		$this->survival = new SurvivalCore($plugin);
		$this->duel = new DuelCore();
	}

	public function event(PlayerInteractEvent $event): void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$this->ffapvp->FFAPvPKit($player, $block);
		$this->speedcorepvp->GameJoin($player, $block);
		$this->speedcorepvp->Interact($event);
		$this->survival->Join($event);
		$this->duel->Join($event);
	}
}
