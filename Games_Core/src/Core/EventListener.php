<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/17
 * Time: 14:41
 */

namespace Core;

use Core\Event\DataPacketReceive;
use Core\Event\PlayerDeath;
use Core\Event\PlayerJoin;
use Core\Event\PlayerLogin;
use Core\Event\PlayerQuit;
use Core\Game\FFAPvP\FFAPvPCore;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
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
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->ffapvp = new FFAPvPCore($this->plugin);
        $this->playerjoinevent = new PlayerJoin($this->plugin);
        $this->playerquitevent = new PlayerQuit($this->plugin);
        $this->playerloginevent = new PlayerLogin($this->plugin);
        $this->playerdeathevent = new PlayerDeath($this->plugin);
        $this->datapacketreceiveevent = new DataPacketReceive($this->plugin);
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
    public function onReceive(DataPacketReceiveEvent $event) {
        $this->datapacketreceiveevent->event($event);
    }
}
