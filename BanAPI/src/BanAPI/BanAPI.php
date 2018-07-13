<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/13
 * Time: 21:02
 */

namespace BanAPI;


use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class BanAPI extends PluginBase
{
    public function onEnable() : void
    {
        $this->getLogger()->info('BanAPIを読み込みました。');
    }

    public function addBan(Player $player, string $reason, string $by)
    {
        $bantime = date("Y/m/d H:i:s");
        $player->kick($reason, true);
        $this->getServer()->getIPBans()->addBan($player->getAddress(), $reason, $bantime, $player->getName());
        $this->getServer()->getNameBans()->addBan($player, $reason, $bantime, $player->getName());
        $this->getLogger()->info($player->getName()."をBANしました。");
        $this->getServer()->broadcastMessage($player->getName()." は $by によってBANされました。\n理由: $reason");
    }
    public function unBan(Player $player) {
        $this->getServer()->getIPBans()->remove($player);
        $this->getServer()->getNameBans()->remove($player);
        $this->getLogger()->info($player->getName()."のBANを解除しました。");
    }
}