<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/17
 * Time: 12:50
 */

namespace Core\Commands;

use Core\DataFile;
use Core\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class stats extends PluginCommand
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        parent::__construct("stats", $plugin);
        $this->setPermission("vector.network.player");
        $this->setDescription("自分のステータスを表示します。");
        $this->plugin = $plugin;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$this->plugin->isEnabled()) {
            return false;
        }
        if (!$this->testPermission($sender)) {
            return false;
        }
        if ($sender instanceof Player) {
            $name = $sender->getName();
            $datafile = new DataFile($name);
            $data = $datafile->get('userdata');
            $level = $data['networklevel'];
            $money = $data['money'];
            $exp = $data['exp'];
            $firstlogin = $data['firstlogin'];
            $lastlogin = $data['lastlogin'];
            $maxexp = $data['maxexp'];
            $status = [
                "type" => "custom_form",
                "title" => "$name のステータス",
                "content" => [
                    [
                        "type" => "label",
                        "text" => "現在のレベル: $level\n現在の経験値: $exp (次のレベルアップまで: $maxexp xp必要です。)\nVNCoin: $money\n参加した日: $firstlogin\n最終ログイン日: $lastlogin"
                    ]
                ]
            ];
            $modal = new ModalFormRequestPacket();
            $modal->formId = mt_rand(1111111,9999999);
            $modal->formData = json_encode($status);
            $sender->dataPacket($modal);
            return true;
        }
        $sender->sendMessage(TextFormat::RED."このコマンドはプレイヤーのみが実行できます。");
        return true;
    }
}
