<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 12:28
 */

namespace Core\Event;

use Core\Game\FFAPvP\FFAPvPCore;
use Core\Main;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\Player;

class PlayerDeath
{
    protected $plugin;
    protected $ffapvp;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->ffapvp = new FFAPvPCore($this->plugin);
    }
    public function event(PlayerDeathEvent $event)
    {
        $event->setDeathMessage(null);
        $player = $event->getPlayer();
        $cause = $player->getLastDamageCause();
        $this->ffapvp->AddDeathCount($player);
        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            if ($damager instanceof Player) {
                $this->ffapvp->AddKillCount($damager);
            }
        }
    }
}
