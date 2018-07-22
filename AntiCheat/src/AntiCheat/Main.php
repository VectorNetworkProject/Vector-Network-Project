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
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    private $banapi;
    public function onEnable() : void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->banapi = $this->getServer()->getPluginManager()->getPlugin("BanAPI");
    }

    public function onToggleFlight(PlayerToggleFlightEvent $event) : void
    {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            if ($event->isFlying()) {
                $this->banapi->addBan($player, "Flying", "AntiCheat");
            } else {
                $this->banapi->addBan($player, "Flying", "AntiCheat");
            }
        }
    }
    public function onReceive(DataPacketReceiveEvent $event)
    {
        $packet = $event->getPacket();
        if ($packet instanceof LoginPacket) {
            if ($packet->clientId === 0) {
                $player = $event->getPlayer();
                $this->banapi->addBan($player, "Toolbox", "AntiCheat");
            }
        }
    }
}
