<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/17
 * Time: 14:41
 */

namespace Core;

use Core\Entity\Bossbar;
use Core\Player\PlayerAddressChecker;
use pocketmine\event\Listener;
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
        $event->setJoinMessage(null);
        $bossbar = new Bossbar("§l§6Vector §bNetwork §eProject", 100, 100);
        $bossbar->sendBar($event->getPlayer());
        $event->getPlayer()->addTitle("§6Vector §bNetwork", "§eDeveloped by InkoHX", 3, 5, 3);
    }
    public function onQuit(PlayerQuitEvent $event)
    {
        $event->setQuitMessage(null);
        $player = $event->getPlayer();
        $data = new DataFile($player->getName());
        $user['lastlogin'] = date('Y:m:d_H:i:s');
        $data->write('userdata', $user);
    }
    public function onPreLogin(PlayerPreLoginEvent $event)
    {
        $player = $event->getPlayer();
        $check = new PlayerAddressChecker();
        if ($check->Checker($player->getAddress())) {
            $this->plugin->getLogger()->info($player->getName()."は国内からのアクセスです。");
        } else {
            $player->kick("§l§6Vector §bNetwork\n§r§fあなたはサーバーからキックされました。\n§7理由: §f国外からのアクセス", false);
            $this->plugin->getLogger()->info($player->getName()."は国外からのアクセスの為キックしました。");
        }
    }
    public function onLogin(PlayerLoginEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $data = new DataFile($name);
        if (($user = $data->get('userdata')) === null) {
            $user = [
                'name' => $name,
                'money' => 0,
                'networklevel' => 1,
                'exp' => 0,
                'firstlogin' => date('Y:m:d_H:i:s'),
                'lastlogin' => date('Y:m:d_H:i:s'),
            ];
            $data->write('userdata', $user);
        }
    }
}
