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

class Main extends PluginBase implements Listener
{
    public function onEnable() : void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onToggleFlight(PlayerToggleFlightEvent $event) : void
    {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            if ($event->isFlying()) {
                $player->kick(TextFormat::RED."[AntiCheat]".TextFormat::YELLOW." あなたはBANされました。\n".TextFormat::RED."理由: Flying", false);
                $player->setBanned(true);
            } else {
                $player->kick(TextFormat::RED."[AntiCheat]".TextFormat::YELLOW." あなたはBANされました。\n".TextFormat::RED."理由: Flying", false);
                $player->setBanned(true);
            }
        }
    }
}
