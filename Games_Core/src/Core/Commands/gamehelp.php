<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/12
 * Time: 13:59
 */

namespace Core\Commands;

use Core\{
    Main
};
use pocketmine\{
    command\Command, command\CommandSender, network\mcpe\protocol\ModalFormRequestPacket, Player, utils\TextFormat
};

class gamehelp extends Command
{
    /**
     * gamehelp constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        parent::__construct("gamehelp", "Vector Network のコマンド一覧を表示します。", "使い方: /gamehelp");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool
    {
        if ($sender instanceof Player) {
            $helpmeaage = [];
            $packet = new ModalFormRequestPacket();
            $packet->formId = 15674;
            $packet->formData = json_encode($helpmeaage);
            $sender->dataPacket($packet);
            return true;
        } else {
            $sender->sendMessage(TextFormat::RED."このコマンドはゲーム内で実行して下さい。");
            return true;
        }
    }
}
