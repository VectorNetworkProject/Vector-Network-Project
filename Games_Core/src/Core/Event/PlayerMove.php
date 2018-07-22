<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 17:12
 */

namespace Core\Event;

use Core\Main;
use pocketmine\event\player\PlayerMoveEvent;

class PlayerMove
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function event(PlayerMoveEvent $event) {}
}
