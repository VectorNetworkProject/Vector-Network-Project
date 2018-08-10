<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/10
 * Time: 17:03
 */

namespace Core\Commands;


use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class gamestatus extends PluginCommand
{
	public function __construct(Plugin $plugin)
	{
		parent::__construct("gamestatus", $plugin);
		$this->setPermission("vector.network.player");
		$this->setDescription("どのゲームに何人居るか表示します。");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$this->getPlugin()->isEnabled()) {
			return false;
		}
		if (!$this->testPermission($sender)) {
			return false;
		}
		if (!$sender instanceof Player) {
			$sender->sendMessage(TextFormat::RED."このコマンドはプレイヤーのみが実行できます。");
			return true;
		}
		$survival = count($this->getPlugin()->getServer()->getLevelByName('Survival')->getPlayers());
		$speedcorepvp = count($this->getPlugin()->getServer()->getLevelByName('corepvp')->getPlayers());
		$ffapvp = count($this->getPlugin()->getServer()->getLevelByName('ffapvp')->getPlayers());
		$lobby = count($this->getPlugin()->getServer()->getLevelByName('lobby')->getPlayers());
		$ui = [
			"type" => "custom_form",
			"title" => "ゲームステータス",
			"content" => [
				[
					"type" => "label",
					"text" => "§6ロビー§r: $lobby 人\n§6FFA§cPvP§r: $ffapvp 人\n§bSpeed§aCore§cPvP§r: $speedcorepvp 人\n§aSurvival§r: $survival 人"
				]
			]
		];
		$modal = new ModalFormRequestPacket();
		$modal->formId = mt_rand(111111111, 9999999999);
		$modal->formData = json_encode($ui);
		$sender->sendDataPacket($modal, false);
		return true;
	}

	public function getPlugin(): Plugin
	{
		return parent::getPlugin();
	}
}