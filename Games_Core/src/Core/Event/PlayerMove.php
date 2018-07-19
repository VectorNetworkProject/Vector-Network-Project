<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 17:12
 */

namespace Core\Event;


use Core\Entity\Bossbar;
use Core\Main;
use Core\Player\Level;
use Core\Player\Money;
use pocketmine\event\player\PlayerMoveEvent;

class PlayerMove
{
    protected $money, $level, $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->money = new Money();
        $this->level = new Level();
    }

    public function event(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $money = $this->money->getMoney($player->getName());
        $level = $this->level->getLevel($player->getName());
        $bossbar = new Bossbar("   §l§6Vector §bNetwork §eProject\n\n§l§eMoney: $money §bNetworkLevel: $level", 100,100);
        $bossbar->BossbarUpdate($player);
    }
}