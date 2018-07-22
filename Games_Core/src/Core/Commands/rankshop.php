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
                "type" => "custom_form",
                "title" => "RankShop",
                "content" => [
                    [
                        "type" => "dropdown",
                        "text" => "§6V§bN§eCoin§rを消費してランクを買う事が出来ます。",
                        "options" => ["§6V§bN", "§5S", "§6A", "§cB", "§aC", "§3D", "§7E"]
                    ],
                    [
                        "type" => "label",
                        "text" => "§6V§bN  §61500000 §eCoin\n§5S  §61000000 §eCoin\n§6A  §6700000 §eCoin\n§cB  §6500000 §eCoin\n§aC  §6300000 §eCoin\n§3D  §6100000 §eCoin\n§7E  §650000 §eCoin"
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
