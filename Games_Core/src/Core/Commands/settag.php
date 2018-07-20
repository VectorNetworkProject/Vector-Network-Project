<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 12:44
 */

namespace Core\Commands;


use Core\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class settag extends PluginCommand
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        parent::__construct("settag", $plugin);
        $this->setPermission("vector.network.player");
        $this->setDescription("§6V§bN§eCoin§rを使ってタグを設定します。");
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
            $settag = [
                "type" => "custom_form",
                "title" => "Tag設定メニュー",
                "content" => [
                    [
                      "type" => "label",
                      "text" => "§6V§bN§eCoin§rを§61000§r消費してタグを設定します。"
                    ],
                    [
                        "type" => "dropdown",
                        "text" => "タグの色を選択して下さい(選択してない場合は色なしになります。)",
                        "options" => ["§0黒色", "§1暗い青色", "§2緑色", "§3暗い水色", "§4暗い赤色", "§5紫色", "§6金色", "§7灰色", "§8青色", "§a緑色", "§b水色", "§c赤色", "§d桃色", "§e黄色", "§f白色"]
                    ],
                    [
                        "type" => "input",
                        "text" => "設定するタグの名前を入力してください。",
                        "placeholder" => "最大8文字"
                    ]
                ]
            ];
            $modal = new ModalFormRequestPacket();
            $modal->formId = 8489612;
            $modal->formData = json_encode($settag);
            $sender->dataPacket($modal);
            return true;
        }
        $sender->sendMessage(TextFormat::RED."このコマンドはプレイヤーのみが実行できます。");
        return true;
    }
}