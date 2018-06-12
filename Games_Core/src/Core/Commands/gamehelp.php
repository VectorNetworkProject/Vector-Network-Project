<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/12
 * Time: 13:59
 */

namespace Core\Commands;


use Core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

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
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            $helpmeaage = [];
            $packet = new ModalFormRequestPacket();
            $packet->formId = 15674;
            $packet->formData = json_encode($helpmeaage);
            $sender->dataPacket($packet);
        } else {
            $sender->sendMessage(TextFormat::RED."このコマンドはゲーム内で実行して下さい。");
        }
    }
}