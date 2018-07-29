<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/17
 * Time: 14:41
 */

namespace Core;

use Core\Event\BlockBreak;
use Core\Event\BlockPlace;
use Core\Event\DataPacketReceive;
use Core\Event\EntityDamage;
use Core\Event\PlayerCommandPreprocess;
use Core\Event\PlayerDeath;
use Core\Event\PlayerInteract;
use Core\Event\PlayerJoin;
use Core\Event\PlayerLogin;
use Core\Event\PlayerMove;
use Core\Event\PlayerPreLogin;
use Core\Event\PlayerQuit;
use Core\Event\PlayerRespawn;
use Core\Game\FFAPvP\FFAPvPCore;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

class EventListener implements Listener
{
	private $plugin = null;
	protected $ffapvp;
	protected $playerjoinevent;
	protected $playerquitevent;
	protected $playerloginevent;
	protected $playerdeathevent;
	protected $datapacketreceiveevent;
	protected $playerprelogin;
	protected $playermoveevent;
	protected $entitydamage;
	protected $blockbreakevent;
	protected $blockplaceevent;
	protected $playerinteractevent;
	protected $playercommandpreprocessevent;
	protected $playerrespawnevent;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->ffapvp = new FFAPvPCore($this->plugin);
		$this->playerjoinevent = new PlayerJoin($this->plugin);
		$this->playerquitevent = new PlayerQuit($this->plugin);
		$this->playerloginevent = new PlayerLogin($this->plugin);
		$this->playerdeathevent = new PlayerDeath($this->plugin);
		$this->datapacketreceiveevent = new DataPacketReceive($this->plugin);
		$this->playerprelogin = new PlayerPreLogin($this->plugin);
		$this->playermoveevent = new PlayerMove($this->plugin);
		$this->entitydamage = new EntityDamage($this->plugin);
		$this->blockbreakevent = new BlockBreak($this->plugin);
		$this->blockplaceevent = new BlockPlace($this->plugin);
		$this->playerinteractevent = new PlayerInteract($this->plugin);
		$this->playercommandpreprocessevent = new PlayerCommandPreprocess($this->plugin);
		$this->playerrespawnevent = new PlayerRespawn($this->plugin);
	}

	public function onJoin(PlayerJoinEvent $event)
	{
		$this->playerjoinevent->event($event);
	}

	public function onQuit(PlayerQuitEvent $event)
	{
		$this->playerquitevent->event($event);
	}

	public function onLogin(PlayerLoginEvent $event)
	{
		$this->playerloginevent->event($event);
	}

	public function onDeath(PlayerDeathEvent $event)
	{
		$this->playerdeathevent->event($event);
	}

	public function onReceive(DataPacketReceiveEvent $event)
	{
		$this->datapacketreceiveevent->event($event);
	}

	public function pnPreLogin(PlayerPreLoginEvent $event)
	{
		$this->playerprelogin->event($event);
	}

	public function onMove(PlayerMoveEvent $event)
	{
		$this->playermoveevent->event($event);
	}

	public function onEntityDamage(EntityDamageEvent $event)
	{
		$this->entitydamage->event($event);
	}

	public function onBreak(BlockBreakEvent $event)
	{
		$this->blockbreakevent->event($event);
	}

	public function onPlace(BlockPlaceEvent $event)
	{
		$this->blockplaceevent->event($event);
	}

	public function onInteract(PlayerInteractEvent $event)
	{
		$this->playerinteractevent->event($event);
	}

	public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event)
	{
		$this->playercommandpreprocessevent->event($event);
	}

	public function onRespawn(PlayerRespawnEvent $event)
	{
		$this->playerrespawnevent->event($event);
	}
}
