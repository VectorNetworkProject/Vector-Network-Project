<?php
/**
 * Created by PhpStorm.
 * User: PCink
 * Date: 2018/07/17
 * Time: 12:49
 */

namespace Core\Commands;

use Core\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ping extends PluginCommand
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        parent::__construct("ping", $plugin);
		$this->setPermission("vector.network.player");
		$this->setDescription("応答速度を計測します。");
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
        	$ping = $sender->getPing();
        	$color = ($ping < 150 ? TextFormat::GREEN : ($ping < 250 ? TextFormat::GOLD : TextFormat::RED));
            $sender->sendMessage(TextFormat::YELLOW."Pong! $color".$ping."§ems");
            return true;
        }
        $sender->sendMessage(TextFormat::RED."このコマンドはプレイヤーのみが実行できます。");
        return true;
    }
}
