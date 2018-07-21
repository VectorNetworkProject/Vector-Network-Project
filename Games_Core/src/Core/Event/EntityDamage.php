<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 15:28
 */

namespace Core\Event;

use Core\Main;
use pocketmine\event\entity\EntityDamageEvent;

class EntityDamage
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function event(EntityDamageEvent $event)
    {
        /*
        $entity = $event->getEntity();
        if ($entity->getLevel()->getName() === "lobby") {
            $event->setCancelled(true);
        }
        */
    }
}
