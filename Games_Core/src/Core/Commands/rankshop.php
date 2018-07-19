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
use pocketmine\plugin\Plugin;
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
            $name = $sender->getName();
            $rankmenu = [
                "type" => "form",
                "title" => "RankShop",
                "content" => "§6V§bN§eCoin§rを使ってランクを買う事が出来ます。",
                "buttons" => [
                    [
                        "text" => "§6V§bN\n§r1500000 §eCoin"
                    ],
                    [
                        "text" => "§5S\n§r1000000 §eCoin"
                    ],
                    [
                        "text" => "§6A\n§r700000 §eCoin"
                    ],
                    [
                        "text" => "§cB\n§r500000 §eCoin"
                    ],
                    [
                        "text" => "§aC\n§r300000 §eCoin"
                    ],
                    [
                        "text" => "§3D\n§r100000 §eCoin"
                    ],
                    [
                        "text" => "§7E\n§r50000 §eCoin"
                    ]
                ]
            ];
            $modal = new ModalFormRequestPacket();
            $modal->formId = 45661984;
            $modal->formData = json_encode($rankmenu);
            $sender->dataPacket($modal);
        }
        $sender->sendMessage(TextFormat::RED."このコマンドはプレイヤーのみが実行できます。");
        return true;
    }
}