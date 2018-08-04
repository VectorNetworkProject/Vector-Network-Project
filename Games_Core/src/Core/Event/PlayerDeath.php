<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 12:28
 */

namespace Core\Event;

use Core\Game\FFAPvP\FFAPvPCore;
use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Main;
use Core\Player\KillSound;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\Item;
use pocketmine\Player;

class PlayerDeath
{
	protected $plugin;
	protected $ffapvp;
	protected $killsound;
	protected $speedcorepvp;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->ffapvp = new FFAPvPCore($this->plugin);
		$this->speedcorepvp = new SpeedCorePvPCore($this->plugin);
		$this->killsound = new KillSound($this->plugin);
	}

	public function event(PlayerDeathEvent $event)
	{
		$event->setDeathMessage(null);
		$player = $event->getPlayer();
		$cause = $player->getLastDamageCause();
		if ($player->getLevel()->getName() === "ffapvp") {
			$event->setDrops([Item::get(0, 0, 0)]);
			$player->setMaxHealth(20);
			if ($cause instanceof EntityDamageByEntityEvent) {
				$damager = $cause->getDamager();
				if ($damager instanceof Player) {
					$this->ffapvp->AddKillCount($damager);
					$this->DeathMessage('ffapvp', $player->getName(), $damager->getName());
					if (!$damager->getMaxHealth() <= 40) {
						$damager->setMaxHealth($damager->getMaxHealth() + 1);
					}
					$damager->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 1));
					$this->killsound->PlaySound($damager);
				}
			} else {
				$this->DeathMessage('ffapvp', $player->getName());
				$this->ffapvp->AddDeathCount($player);
			}
		} elseif ($player->getLevel()->getName() === "corepvp") {
			if ($cause instanceof EntityDamageByEntityEvent) {
				$damager = $cause->getDamager();
				if ($damager instanceof Player) {
					$this->speedcorepvp->AddKillCount($damager);
					$this->killsound->PlaySound($damager);
					$this->DeathMessage('corepvp', $player->getName(), $damager->getName());
				}
			} else {
				$this->DeathMessage('corepvp', $player->getName());
				$this->speedcorepvp->AddDeathCount($player);
			}
		}
	}

	public function DeathMessage(string $levelname, string $killed = null, string $killer = null)
	{
		foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
			if ($player->getLevel()->getName() === $levelname) {
				if ($killer === null) {
					$player->sendMessage("§7[§b情報§7] §c✖ §7$killed");
				} else {
					$player->sendMessage("§7[§b情報§7] §7$killer §c➡ §7$killed");
				}
			}
		}
	}
}
