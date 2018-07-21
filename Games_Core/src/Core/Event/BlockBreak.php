<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 15:44
 */

namespace Core\Event;

use Core\Main;
use pocketmine\event\block\BlockBreakEvent;

class BlockBreak
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function event(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $event->setCancelled(true);
        }
    }
}
