<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 12:25
 */

namespace Core\Event;

use Core\DataFile;
use Core\Main;
use pocketmine\event\player\PlayerLoginEvent;

class PlayerLogin
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function event(PlayerLoginEvent $event)
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
                "rank" => "NoRank",
                "tag" => "NoTag",
                "firstlogin" => date("Y年m月d日 H時i分s秒"),
                "lastlogin" => date("Y年m月d日 H時i分s秒")
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
}
