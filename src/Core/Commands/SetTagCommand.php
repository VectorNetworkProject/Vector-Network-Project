<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 12:44
 */

namespace Core\Commands;

use Core\Main;
use Core\Player\Money;
use Core\Player\Tag;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use tokyo\pmmp\libform\element\Dropdown;
use tokyo\pmmp\libform\element\Input;
use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\FormApi;

class SetTagCommand extends PluginCommand
{
	private $plugin;
	private $money;
	private $tag;

	public function __construct(Main $plugin)
	{
		parent::__construct("settag", $plugin);
		$this->setPermission("vector.network.player");
		$this->setDescription("§6V§bN§eCoin§rを使ってタグを設定します。");
		$this->plugin = $plugin;
		$this->money = new Money();
		$this->tag = new Tag();
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
				if (empty($response[2])) {
					$player->sendMessage("§7[§c失敗§7] §cタグ名を記入して下さい。");
				} else {
					if ($this->money->reduceMoney($player->getName(), 1000)) {
						$player->sendMessage("§7[§b情報§7] §6V§bN§eCoin§7を§61000§7消費しました。");
						$this->tag->setTag($player, $response[2], self::getColor($response[1]));
					} else {
						$player->sendMessage(MessagesEnum::BUY_ERROR);
					}
				}
			}
		})->setTitle("Tag設定メニュー")
			->addElement(new Label("§6V§bN§eCoin§rを§61000§r消費してタグを設定します。"))
			->addElement(new Dropdown("タグの色を選択して下さい(選択してない場合は色なしになります。)", ["§0黒色", "§1濃い青色", "§2濃い緑色", "§3濃い水色", "§4濃い赤色", "§5紫色", "§6金色", "§7灰色", "§8灰色", "§9青色", "§a緑色", "§b水色", "§c赤色", "§d桃色", "§e黄色", "§f白色", "色無し"]))
			->addElement(new Input("設定するタグの名前を入力してください。", "最大8文字"))
			->sendToPlayer($sender);
		return true;
	}

	private static function getColor(string $color = null): int
	{
		switch ($color) {
			case "§0黒色":
				return 0;
				break;
			case "§1濃い青色":
				return 1;
				break;
			case "§2濃い緑色":
				return 2;
				break;
			case "§3濃い水色":
				return 3;
				break;
			case "§4濃い赤色":
				return 4;
				break;
			case "§5紫色":
				return 5;
				break;
			case "§6金色":
				return 6;
				break;
			case "§7灰色":
				return 7;
				break;
			case "§8灰色":
				return 8;
				break;
			case "§9青色":
				return 9;
				break;
			case "§a緑色":
				return 10;
				break;
			case "§b水色":
				return 11;
				break;
			case "§c赤色":
				return 12;
				break;
			case "§d桃色":
				return 13;
				break;
			case "§e黄色":
				return 14;
				break;
			case "§f白色":
				return 15;
				break;
			case "色無し":
				return 16;
				break;
			default:
				return 16;
				break;
		}
	}
}
