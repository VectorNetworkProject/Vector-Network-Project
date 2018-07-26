<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/13
 * Time: 13:39
 */

namespace AntiCheat;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
	private $banapi;
	protected $spamplayers = [];

	public function onEnable(): void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->banapi = $this->getServer()->getPluginManager()->getPlugin("BanAPI");
	}

	public function onToggleFlight(PlayerToggleFlightEvent $event): void
	{
		$player = $event->getPlayer();
		if (!$player->isOp()) {
			if ($event->isFlying()) {
				$this->banapi->addBan($player, "Flying", "AntiCheat", true);
			} else {
				$this->banapi->addBan($player, "Flying", "AntiCheat", true);
			}
		}
	}

	public function onReceive(DataPacketReceiveEvent $event)
	{
		$packet = $event->getPacket();
		if ($packet instanceof LoginPacket) {
			if ($packet->clientId === 0) {
				$player = $event->getPlayer();
				$this->banapi->addBan($player, "Toolbox", "AntiCheat", true);
			}
		}
	}

	public function onCommandPreprocess(PlayerCommandPreprocessEvent $event)
	{
		$player = $event->getPlayer();
		$cooldown = microtime(true);
		if (isset($this->splayers[$player->getName()])) {
			if (($cooldown - $this->spamplayers[$player->getName()]['cooldown']) < 5) {
				$player->sendMessage("§7クールダウン中です。");
				$event->setCancelled(true);
			}
		}
		$this->spamplayers[$player->getName()]["cooldown"] = $cooldown;
	}

	public function onDamage(EntityDamageEvent $event)
	{
		$entity = $event->getEntity();
		if ($event instanceof EntityDamageByEntityEvent and $entity instanceof EntityDamageByEntityEvent) {
			$damager = $event->getDamager();
			if ($damager instanceof Player) {
				if ($damager->getGamemode() === Player::CREATIVE) {
					return;
				}
				if ($damager->distance($entity) > 3.9) {
					$event->setCancelled(true);
				}
			}
		}
	}
}
