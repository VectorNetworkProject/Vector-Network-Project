<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/13
 * Time: 13:39
 */

namespace AntiCheat;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use uramnoil\tban\TBan;

class Main extends PluginBase implements Listener
{
    public function onEnable() : void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onToggleFlight(PlayerToggleFlightEvent $event) : void
    {
        $bantime = new \DateTime('3000/1/1 00:00:00');
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            if ($event->isFlying()) {
                $player->kick(TextFormat::RED."[AntiCheat]".TextFormat::YELLOW." あなたはBANされました。\n".TextFormat::RED."理由: Flying", false);
                TBan::create($player->getName(), $bantime);
            } else {
                $player->kick(TextFormat::RED."[AntiCheat]".TextFormat::YELLOW." あなたはBANされました。\n".TextFormat::RED."理由: Flying", false);
                TBan::create($player->getName(), $bantime);
            }
        }
    }
}
