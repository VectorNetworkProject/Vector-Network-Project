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
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\libform\element\Dropdown;
use tokyo\pmmp\libform\FormApi;

class killsound extends PluginCommand
{
	private static $sounds = [
		"サウンド無し",
		"チーン",
		"1UP",
		"骨が折れる音",
		"デデドン",
		"ピチューン",
		"ブスッ",
		"許してくれたまえ",
		"さっさと逃げればいいものを"
	];
	private $plugin;
	private $killsound;

	public function __construct(Main $plugin)
	{
		parent::__construct("killsound", $plugin);
		$this->setPermission("vector.network.player");
		$this->setDescription("敵を倒した時のサウンドを設定します。");
		$this->plugin = $plugin;
		$this->killsound = new \Core\Player\KillSound($plugin);
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
		FormApi::makeCustomForm(function (Player $player, ?array $response) {
			if (!FormApi::formCancelled($response)) {
				switch ($response[0]) {
					case self::$sounds[0]:
						$this->killsound->setKillSound($player, 0);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを無効にしました。");
						break;
					case self::$sounds[1]:
						$this->killsound->setKillSound($player, 1);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【チーン】に設定しました。");
						break;
					case self::$sounds[2]:
						$this->killsound->setKillSound($player, 2);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【1UP】に設定しました。");
						break;
					case self::$sounds[3]:
						$this->killsound->setKillSound($player, 3);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【骨が折れる音】に設定しました。");
						break;
					case self::$sounds[4]:
						$this->killsound->setKillSound($player, 4);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【デデドン】に設定しました。");
						break;
					case self::$sounds[5]:
						$this->killsound->setKillSound($player, 5);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【ピチューン】に設定しました。");
						break;
					case self::$sounds[6]:
						$this->killsound->setKillSound($player, 6);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【ブスッ】に設定しました。");
						break;
					case self::$sounds[7]:
						$this->killsound->setKillSound($player, 7);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【許してくれたまえ】に設定しました。");
						break;
					case self::$sounds[8]:
						$this->killsound->setKillSound($player, 8);
						$player->sendMessage("§7[§a成功§7] §aキルサウンドを【さっさと逃げればいいものを】に設定しました。");
						break;
				}
			}
		})->setTitle("キルサウンド選択")
			->addElement(new Dropdown("敵を倒したときに出るサウンドを設定します。", self::$sounds))
			->sendToPlayer($sender);
		return true;
	}
}
