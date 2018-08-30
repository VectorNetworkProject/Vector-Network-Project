<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/17
 * Time: 14:41
 */

namespace Core\Event;

use Core\DataFile;
use Core\Discord\Discord;
use Core\Entity\Bossbar;
use Core\Entity\GameMaster;
use Core\Entity\Mazai;
use Core\Entity\MazaiMaster;
use Core\Game\Athletic\AthleticCore;
use Core\Game\FFAPvP\FFAPvPCore;
use Core\Game\SpeedCorePvP\SpeedCorePvPCore;
use Core\Game\Survival\SurvivalCore;
use Core\Main;
use Core\Player\KillSound;
use Core\Player\Level;
use Core\Player\Rank;
use Core\Player\Tag;
use Core\Task\JoinTitle;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityInventoryChangeEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerAchievementAwardedEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class EventListener implements Listener
{
	private $plugin = null;
	protected $ffapvp;
	protected $speedCorePvp;
	protected $athletic;
	protected $survival;
	protected $mazaiNpc;
	protected $gameMasterNpc;
	protected $mazaiMasterNpc;
	private $killSound;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->ffapvp = new FFAPvPCore($this->plugin);
		$this->speedCorePvp = new SpeedCorePvPCore($this->plugin);
		$this->athletic = new AthleticCore();
		$this->survival = new SurvivalCore($this->plugin);
		$this->mazaiNpc = new Mazai();
		$this->gameMasterNpc = new GameMaster($this->plugin);
		$this->mazaiMasterNpc = new MazaiMaster();
		$this->survival = new SurvivalCore($this->plugin);
		$this->killSound = new KillSound($this->plugin);
	}

	public function onJoin(PlayerJoinEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$level = (new Level())->getLevel($name);
		$rank = (new Rank($this->plugin))->getRank($name);
		$tag = (new Tag())->getTag($player);
		$event->setJoinMessage("§b[§a参加§b] §7$name が参加しました。");
		$bossBar = new Bossbar();
		$bossBar->sendBar($player);
		$player->setNameTag("§7[§r $rank §7] §r$name");
		$player->setDisplayName("§7[§r $rank §7][ §rLv.$level §7][§r $tag §7] §r$name");
		$this->plugin->getScheduler()->scheduleDelayedTask(new JoinTitle($this->plugin, $player), 100);
		$player = $event->getPlayer();
		$this->mazaiNpc->Create($player, "§a魔剤§e売りの§a魔剤§eさん", "MazaiNPC", new Vector3(260, 4, 265), Item::get(Item::POTION, 11, 1), Mazai::ENTITY_ID);
		$this->gameMasterNpc->Create($player, "§aGame§7Master", "GameMaster", new Vector3(252, 4, 265), Item::get(Item::COMPASS, 0, 1), GameMaster::ENTITY_ID);
		$this->mazaiMasterNpc->Create($player, "§a魔剤§7マスター", "MazaiNPC", new Vector3(287, 10, 270), Item::get(Item::POTION, 11, 1), MazaiMaster::ENTITY_ID);
	}

	public function onQuit(PlayerQuitEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$event->setQuitMessage("§b[§c退出§b] §7$name が退出しました。");
		$bossBar = new Bossbar();
		$bossBar->RemoveBar($player);
		$data = new DataFile($player->getName());
		$user = $data->get("USERDATA");
		$user["lastlogin"] = date("Y年m月d日 H時i分s秒");
		$data->write("USERDATA", $user);
		$player = $event->getPlayer();
		$this->speedCorePvp->GameQuit($player);
		$this->survival->SaveData($event);
		$this->mazaiNpc->Remove($player, Mazai::ENTITY_ID);
		$this->gameMasterNpc->Remove($player, GameMaster::ENTITY_ID);
		$this->mazaiMasterNpc->Remove($player, MazaiMaster::ENTITY_ID);
		// $this->athletic->onQuit($event);
	}

	public function onLogin(PlayerLoginEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$data = new DataFile($name);
		if (($user = $data->get("USERDATA")) === null) {
			$user = [
				"name" => $name,
				"money" => 1000,
				"networklevel" => 1,
				"exp" => 0,
				"maxexp" => 50,
				"rank" => "§rNoRank",
				"tag" => "§rNoTag",
				"mazaipoint" => 0,
				"killsound" => 0,
				"firstlogin" => date("Y年m月d日 H時i分s秒"),
				"lastlogin" => date("Y年m月d日 H時i分s秒")
			];
			$data->write("USERDATA", $user);
			$this->plugin->getServer()->broadcastMessage("§7[§b情報§7] $name は初参加です。");
		}
		if (($ffapvp = $data->get("FFAPVP")) === null) {
			$ffapvp = [
				"name" => $name,
				"kill" => 0,
				"death" => 0
			];
			$data->write('FFAPVP', $ffapvp);
		}
		if (($corepvp = $data->get("COREPVP")) === null) {
			$corepvp = [
				"name" => $name,
				"kill" => 0,
				"death" => 0,
				"breakcore" => 0,
				"win" => 0,
				"lose" => 0
			];
			$data->write('COREPVP', $corepvp);
		}
		if (($inventory = $data->get('SURVIVAL')) === null) {
			$inventory = [
				"breakblock" => 0,
				"placeblock" => 0,
				"kill" => 0,
				"death" => 0,
				"breakdiamond" => 0,
				"breakgold" => 0,
				"breakcoal" => 0,
				"breakiron" => 0,
				"health" => 20,
				"food" => 20,
				"items" => array(),
				"x" => 225,
				"y" => 243,
				"z" => 256
			];
			$data->write('SURVIVAL', $inventory);
		}
		if (($duel = $data->get('DUEL')) === null) {
			$duel = [
				"win" => 0,
				"lose" => 0
			];
			$data->write('DUEL', $duel);
		}
	}

	public function onDeath(PlayerDeathEvent $event)
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
					$this->ffapvp->AddDeathCount($player);
					$this->DeathMessage('ffapvp', $player->getName(), $damager->getName());
					if ($damager->getMaxHealth() < 40) {
						$damager->setMaxHealth($damager->getMaxHealth() + 1);
					}
					$damager->getInventory()->addItem(Item::get(Item::GOLDEN_APPLE, 0, 1));
					$this->killSound->PlaySound($damager);
				}
			} else {
				$this->DeathMessage('ffapvp', $player->getName());
				$this->ffapvp->AddDeathCount($player);
			}
		} elseif ($player->getLevel()->getName() === "corepvp") {
			if ($cause instanceof EntityDamageByEntityEvent) {
				$damager = $cause->getDamager();
				if ($damager instanceof Player) {
					$this->speedCorePvp->AddKillCount($damager);
					$this->speedCorePvp->addDeathCount($player);
					$this->killSound->PlaySound($damager);
					$this->DeathMessage('corepvp', $player->getName(), $damager->getName());
				}
			} else {
				$this->DeathMessage('corepvp', $player->getName());
				$this->speedCorePvp->addDeathCount($player);
			}
		} elseif ($player->getLevel()->getName() === "Survival") {
			if ($cause instanceof EntityDamageByEntityEvent) {
				$damager = $cause->getDamager();
				if ($damager instanceof Player) {
					$this->survival->AddKillCount($damager);
					$this->survival->AddDeathCount($player);
					$this->killSound->PlaySound($damager);
					$this->DeathMessage("Survival", $player->getName(), $damager->getName());
				}
			} else {
				$this->DeathMessage("Survival", $player->getName());
				$this->survival->AddDeathCount($player);
			}
		}
	}

	public function onReceive(DataPacketReceiveEvent $event)
	{
		$this->mazaiNpc->ClickEntity($event);
		$this->gameMasterNpc->ClickEntity($event);
		$this->mazaiMasterNpc->ClickEntity($event);
	}

	public function pnPreLogin(PlayerPreLoginEvent $event)
	{
		$player = $event->getPlayer();
		if ($this->plugin->getServer()->hasWhitelist()) {
			if (!$this->plugin->getServer()->isWhitelisted(strtolower($player->getName()))) {
				$player->close($player->getLeaveMessage(), "     §6Vector §bNetwork\n§7現在ホワイトリストです。\n§7詳しい情報はLobiまたはDiscordから得ることができます。\n§7Discord: https://hxbot.tk/VNP-D\n§7Lobi: https://hxbot.tk/VNP-L");
			}
		}
	}

	public function onMove(PlayerMoveEvent $event)
	{
		$this->athletic->loop($event);
	}

	public function onEntityDamage(EntityDamageEvent $event)
	{
		$entity = $event->getEntity();
		if ($entity->getLevel()->getName() === "ffapvp" or $entity->getLevel()->getName() === "corepvp") {
			if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
				$event->setCancelled(true);
			}
		}
		if ($event instanceof EntityDamageByEntityEvent && $entity instanceof Player) {
			$damager = $event->getDamager();
			if ($damager instanceof Player) {
				if ($damager->getName() === $entity->getName()) {
					$event->setCancelled(true);
				}
			}
		}
		$this->speedCorePvp->Damage($event);
	}

	public function onBreak(BlockBreakEvent $event)
	{
		$this->survival->AddBreakCount($event->getPlayer());
		$this->speedCorePvp->BreakCore($event);
		$this->speedCorePvp->DropItem($event);
		$this->survival->BreakBlock($event);
	}

	public function onPlace(BlockPlaceEvent $event)
	{
		$this->survival->AddPlaceCount($event->getPlayer());
		$this->speedCorePvp->AntiPlace($event);
	}

	public function onInteract(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$this->ffapvp->FFAPvPKit($player, $block);
		$this->speedCorePvp->GameJoin($player, $event->getBlock());
		$this->speedCorePvp->Interact($event);
		$this->survival->Join($event);
		//$this->athletic->isAthleticFinish($event, $event->getPlayer());
		//$this->athletic->touch($event);
		//$this->athletic->getAthleticData($event);
	}

	public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$level = (new Level)->getLevel($name);
		$rank = (new Rank($this->plugin))->getRank($name);
		$tag = (new Tag())->getTag($player);
		$player->setDisplayName("§7[§r $rank §7][ §rLv.$level §7][§r $tag §7] §r$name");
		switch ($event->getMessage()) {
			case '/whitelist on':
				if (!$player->isOp()) return;
				Discord::SendEmbed("SERVER STATUS", "WHITELIST ON", "メンテナンスが開始されました。", 16776960);
				break;
			case '/whitelist off':
				if (!$player->isOp()) return;
				Discord::SendEmbed("SERVER STATUS", "WHITELIST OFF", "メンテナンスが終了しました。", 8847104);
				break;
		}
	}

	public function onRespawn(PlayerRespawnEvent $event)
	{
		$this->speedCorePvp->Respawn($event->getPlayer());
	}

	public function onEntityInventoryChange(EntityInventoryChangeEvent $event)
	{
		$this->speedCorePvp->CancelChange($event);
	}

	public function onEntityShootBow(EntityShootBowEvent $event)
	{
		$event->setForce($event->getForce() + 0.5);
	}

	public function EntityLevelChange(EntityLevelChangeEvent $event)
	{
		$this->speedCorePvp->LevelChange($event);
		$this->survival->LoadData($event);
		$this->mazaiNpc->Check($event);
		$this->gameMasterNpc->Check($event);
		$this->mazaiMasterNpc->Check($event);
	}

	public function onChat(PlayerChatEvent $event)
	{
		$this->speedCorePvp->TeamChat($event);
	}

	public function onPlayerAchievementAwarded(PlayerAchievementAwardedEvent $event)
	{
		$event->setCancelled(true);
	}

	public function onSignChange(SignChangeEvent $event)
	{
		$this->survival->Sign($event);
		$this->speedCorePvp->Sign($event);
	}

	public function onPlayerExhaust(PlayerExhaustEvent $event)
	{
		if ($event->getPlayer()->getLevel()->getName() === 'lobby') {
			$event->setCancelled(true);
		}
	}

	public function DeathMessage(string $levelname, string $killed = null, string $killer = null): void
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
