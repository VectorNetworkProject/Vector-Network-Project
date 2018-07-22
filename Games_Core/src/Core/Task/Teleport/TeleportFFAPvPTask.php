<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 21:12
 */

namespace Core\Task\Teleport;

use Core\Main;
use Core\Task\PluginTask;
use pocketmine\level\Position;
use pocketmine\Player;

class TeleportFFAPvPTask extends PluginTask
{
    protected $player;
    public function __construct(Main $plugin, Player $player)
    {
        parent::__construct($plugin);
        $this->player = $player;
    }
    public function onRun(int $currentTick)
    {
        $this->player->teleport(new Position(255, 4, 255, $this->owner->getServer()->getLevelByName("ffapvp")));
        $this->player->sendMessage("§aテレポートしました。");
    }
}
