<?php
/**
 * Created by PhpStorm.
 * User: souta
 * Date: 2018/06/16
 * Time: 22:06
 */

namespace Core\Checker;


use Core\{
    Main
};

use pocketmine\{
    utils\TextFormat
};

class AdminPlayer extends Main
{
    /**
     * @param $name
     * @param $message
     */
    public function AdminMessage($name, $message) {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            if ($player->isOp()) {
                $player->sendMessage(TextFormat::GOLD."[$name]".TextFormat::GRAY." >> ".TextFormat::YELLOW.$message);
            }
        }
        $this->getLogger()->info(TextFormat::GOLD."[$name]".TextFormat::GRAY." >> ".TextFormat::YELLOW.$message);
    }
}