<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/13
 * Time: 13:39
 *
 * 参考にしたプラグイン: https://github.com/pmmp/AntiInstaBreak
 */

namespace AntiCheat;

use pocketmine\entity\Effect;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
	private $banapi;
	protected $breakcooldown = [];
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
				$this->banapi->addBan($player, "Flying(飛行)", "AntiCheat", true);
			} else {
				$this->banapi->addBan($player, "Flying(飛行)", "AntiCheat", true);
			}
		}
	}

	public function onInteract(PlayerInteractEvent $event)
	{
		if ($event->getAction() === PlayerInteractEvent::LEFT_CLICK_BLOCK) {
			$this->breakcooldown[$event->getPlayer()->getRawUniqueId()] = floor(microtime(true) * 20);
		}
	}

	/* public function onBreak(BlockBreakEvent $event)
	{
		if (!$event->getInstaBreak()) {
			do {
				$player = $event->getPlayer();
				if (!isset($this->breakTimes[$uuid = $player->getRawUniqueId()])) {
					$event->setCancelled(true);
					break;
				}
				$expectedTime = ceil($event->getBlock()->getBreakTime($event->getItem()) * 20);
				if ($player->hasEffect(Effect::HASTE)) {
					$expectedTime *= 1 - (0.2 * $player->getEffect(Effect::HASTE)->getEffectLevel());
				}
				if ($player->hasEffect(Effect::MINING_FATIGUE)) {
					$expectedTime *= 1 + (0.3 * $player->getEffect(Effect::MINING_FATIGUE)->getEffectLevel());
				}
				$expectedTime -= 1;
				$actualTime = ceil(microtime(true) * 20) - $this->breakcooldown[$uuid = $player->getRawUniqueId()];
				if ($actualTime < $expectedTime) {
					$event->setCancelled(true);
					break;
				}
				unset($this->breakcooldown[$uuid]);
			} while (false);
		}
	} */

	public function onPlayerQuit(PlayerQuitEvent $event)
	{
		unset($this->breakcooldown[$event->getPlayer()->getRawUniqueId()]);
	}

	public function onReceive(DataPacketReceiveEvent $event)
	{
		$packet = $event->getPacket();
		if ($packet instanceof LoginPacket) {
			if ($packet->serverAddress === "mcpeproxy.tk" or $packet->serverAddress === "165.227.79.111") {
				$this->banapi->addBan($event->getPlayer(), "PROXY(プロキシ)", "AntiCheat", true);
			}
			if ($packet->clientId === 0) {
				$player = $event->getPlayer();
				$this->banapi->addBan($player, "Toolbox(ツール)", "AntiCheat", true);
			}
		}
	}

	public function onCommandPreprocess(PlayerCommandPreprocessEvent $event)
	{
		$player = $event->getPlayer();
		$cooldown = microtime(true);
		if (isset($this->spamplayers[$player->getName()])) {
			if (($cooldown - $this->spamplayers[$player->getName()]['cooldown']) < 3) {
				$player->sendMessage("§7クールダウン中です。");
				$event->setCancelled(true);
			}
		}
		$this->spamplayers[$player->getName()]["cooldown"] = $cooldown;
	}

	public function onDamage(EntityDamageEvent $event)
	{
		$entity = $event->getEntity();
		if ($event instanceof EntityDamageByEntityEvent and $entity instanceof Player) {
			$damager = $event->getDamager();
			if ($damager instanceof Player) {
				if ($damager->getGamemode() === Player::CREATIVE or $damager->getInventory()->getItemInHand()->getId() === Item::BOW) {
					return;
				}
				if ($damager->distance($entity) > 3.9) {
					$event->setCancelled(true);
				}
			}
		}
	}
}
