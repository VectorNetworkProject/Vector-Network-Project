<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/17
 * Time: 14:41
 */

namespace Core;

use Core\Entity\Bossbar;
use Core\Game\Survival\FFAPvPCore;
use Core\Task\JoinTitle;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener
{
    private $plugin = null;
    protected $ffapvp;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->ffapvp = new FFAPvPCore($this->plugin);
    }
    public function onJoin(PlayerJoinEvent $event)
    {
        $datafile = new DataFile($event->getPlayer()->getName());
        $player = $event->getPlayer();
        $name = $player->getName();
        $event->setJoinMessage("§b[§a参加§b] §7$name が参加しました。");
        $user = $datafile->get("USERDATA");
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
        $user = $data->get("USERDATA");
        $user["lastlogin"] = date("Y:m:d H:i:s");
        $data->write("USERDATA", $user);
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
                "maxexp" => 0,
                "firstlogin" => date("Y:m:d H:i:s"),
                "lastlogin" => date("Y:m:d H:i:s")
            ];
            $data->write("USERDATA", $user);
        }
        if (($ffapvp = $data->get("FFAPVP")) === null) {
            $ffapvp = [
                "name" => $name,
                "kill" => 0,
                "death" => 0
            ];
            $data->write('FFAPVP', $ffapvp);
        }
    }
    public function onDeath(PlayerDeathEvent $event) {
        $this->ffapvp->AddDeathCount($event->getPlayer());
    }
}
