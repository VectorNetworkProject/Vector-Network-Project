<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 11:49
 */

namespace Core\Event;

use Core\DataFile;
use Core\Entity\Bossbar;
use Core\Main;
use Core\Task\JoinTitle;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoin
{
    protected $plugin;
    protected $listener;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function event(PlayerJoinEvent $event)
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
}
