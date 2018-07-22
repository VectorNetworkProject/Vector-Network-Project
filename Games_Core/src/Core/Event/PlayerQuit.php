<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 12:00
 */

namespace Core\Event;

use Core\DataFile;
use Core\Main;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerQuit
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function event(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $event->setQuitMessage("§b[§c退出§b] §7$name が退出しました。");
        $data = new DataFile($player->getName());
        $user = $data->get("USERDATA");
        $user["lastlogin"] = date("Y年m月d日 H時i分s秒");
        $data->write("USERDATA", $user);
    }
}
