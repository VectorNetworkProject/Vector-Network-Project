<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 12:48
 */

namespace Core\Commands;

use Core\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class rankshop extends PluginCommand
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        parent::__construct("rankshop", $plugin);
        $this->setPermission("vector.network.player");
        $this->setDescription("§6V§bN§eCoin§rでランク買います。");
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
            $rankmenu = [
                "type" => "form",
                "title" => "RankShop",
                "content" => "§6V§bN§eCoin§rを使ってランクを買う事が出来ます。",
                "buttons" => [
                    [
                        "text" => "§6V§bN\n§61500000 §eCoin"
                    ],
                    [
                        "text" => "§5S\n§61000000 §eCoin"
                    ],
                    [
                        "text" => "§6A\n§6700000 §eCoin"
                    ],
                    [
                        "text" => "§cB\n§6500000 §eCoin"
                    ],
                    [
                        "text" => "§aC\n§6300000 §eCoin"
                    ],
                    [
                        "text" => "§3D\n§6100000 §eCoin"
                    ],
                    [
                        "text" => "§7E\n§650000 §eCoin"
                    ]
                ]
            ];
            $modal = new ModalFormRequestPacket();
            $modal->formId = 45661984;
            $modal->formData = json_encode($rankmenu);
            $sender->dataPacket($modal);
            return true;
        }
        $sender->sendMessage(TextFormat::RED."このコマンドはプレイヤーのみが実行できます。");
        return true;
    }
}
