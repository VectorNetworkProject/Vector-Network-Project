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
            $helpmeaage = ["type" => "modal", "title" => "§cルール", "content" => "1. MCPEPROXY、ModPE、LuaScriptなどを使ったチート(Hack)の使用は禁止です。見かけた場合すぐにBANします。\n2. 無差別に色んな所でこのサーバを宣伝する事\n3. 不適切な内容が含まれた文を送信・意味不明な文章を連続送信する事も禁止です。\n4. プレイヤー名に不適切な内容を含めて参加する事は禁止です。(名前の変更が確認できたらBANを解除します。)\n5. バグ・ラグの悪用は禁止です。\nこれらを全て理解した上で同意ボタンを押して下さい。", "button1" => "同意する", "button2" => "同意しない"];
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
