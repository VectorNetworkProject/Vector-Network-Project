<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/17
 * Time: 14:41
 */

namespace Core;

use Core\Entity\Bossbar;
use Core\Task\JoinTitle;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener
{
    private $plugin = null;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onJoin(PlayerJoinEvent $event)
    {
        $datafile = new DataFile($event->getPlayer()->getName());
        $player = $event->getPlayer();
        $name = $player->getName();
        $event->setJoinMessage("§b[§a参加§b] §7$name が参加しました。");
        $user = $datafile->get("userdata");
        $money = $user["money"];
        $level = $user["networklevel"];
        $bossbar = new Bossbar("   §l§6Vector §bNetwork §eProject\n\n§l§eMoney: $money §bNetworkLevel: $level", 100, 100);
        $bossbar->sendBar($player);
        $this->plugin->getScheduler()->scheduleDelayedTask(new JoinTitle($this->plugin, $player), 100);
    }
    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $event->setQuitMessage("§b[§c退出§b] §7$name が退出しました。");
        $data = new DataFile($player->getName());
        $user = $data->get("userdata");
        $user["lastlogin"] = date("Y:m:d H:i:s");
        $data->write("userdata", $user);
    }
    public function onPreLogin(PlayerPreLoginEvent $event) {}
    public function onLogin(PlayerLoginEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $data = new DataFile($name);
        if (($user = $data->get("userdata")) === null) {
            $user = [
                "name" => $name,
                "money" => 1000,
                "networklevel" => 1,
                "exp" => 0,
                "maxexp" => 0,
                "firstlogin" => date("Y:m:d H:i:s"),
                "lastlogin" => date("Y:m:d H:i:s")
            ];
            $data->write("userdata", $user);
        }
        if (($survival = $data->get("survival")) === null) {
            $survival = [
                "name" => $name,
                "kill" => 0,
                "death" => 0,
                "break" => 0,
                "place" => 0
            ];
            $data->write('survival', $survival);
        }
    }
    public function onDeath(PlayerDeathEvent $event)
    {
        $event->setDeathMessage(null);
        /*
        $cause = $event->getPlayer()->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent and $cause->getDamager() instanceof Player) {
            $killer = $cause->getDamager();
        }
        */
    }
    public function onPlace(BlockPlaceEvent $event) {}
}
