<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/17
 * Time: 13:10
 */
namespace Core\Task;

use pocketmine\plugin\Plugin;

class Tip extends PluginTask
{
    public function __construct(Plugin $owner)
    {
        parent::__construct($owner);
    }
    public function onRun(int $currentTick)
    {
        $rand = mt_rand(1, 3);
        if ($rand === 1) {
            $this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7このゲームはまだ未完成です。");
        } elseif ($rand === 2) {
            $this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7test2");
        } elseif ($rand === 3) {
            $this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7test3");
        }
    }
}
