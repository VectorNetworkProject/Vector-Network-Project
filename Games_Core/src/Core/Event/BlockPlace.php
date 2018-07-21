<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 15:48
 */

namespace Core\Event;

use Core\Main;
use pocketmine\event\block\BlockPlaceEvent;

class BlockPlace
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function event(BlockPlaceEvent $event)
    {
        /*
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $event->setCancelled(true);
        }
        */
    }
}
