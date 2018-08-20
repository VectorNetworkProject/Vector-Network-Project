<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/24
 * Time: 17:23
 */

namespace Core\Commands;

use Core\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class killsound extends PluginCommand
{
	protected $plugin;

	public function __construct(Main $plugin)
	{
		parent::__construct("killsound", $plugin);
		$this->setPermission("vector.network.player");
		$this->setDescription("敵を倒した時のサウンドを設定します。");
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
		if (!$sender instanceof Player) {
			$sender->sendMessage(TextFormat::RED . "このコマンドはプレイヤーのみが実行できます。");
			return true;
		}
		$killsoundmenu = [
			"type" => "custom_form",
			"title" => "キルサウンド選択",
			"content" => [
				[
					"type" => "dropdown",
					"text" => "敵を倒した時に出るサウンドを設定します。",
					"options" => ["サウンド無し", "チーン", "1UP", "骨が折れる音", "デデドン", "ピチューン", "ブスッ", "許してくれたまえ", "さっさと逃げればいいものを"]
				]
			]
		];
		$modal = new ModalFormRequestPacket();
		$modal->formId = 94572154;
		$modal->formData = json_encode($killsoundmenu);
		$sender->sendDataPacket($modal);
		return true;
	}
}
