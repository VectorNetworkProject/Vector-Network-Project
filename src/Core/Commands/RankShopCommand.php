<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 12:48
 */

namespace Core\Commands;

use Core\Main;
use Core\Player\Money;
use Core\Player\Rank;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\libform\element\Dropdown;
use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\FormApi;

class RankShopCommand extends PluginCommand
{
	private static $ranks = [
		"§6V§bN",
		"§5S",
		"§eA",
		"§cB",
		"§aC",
		"§3D",
		"§7E"
	];
	private $plugin;
	private $money;
	private $rank;

	public function __construct(Main $plugin)
	{
		parent::__construct("rankshop", $plugin);
		$this->setPermission("vector.network.player");
		$this->setDescription("§6V§bN§eCoin§rでランク買います。");
		$this->plugin = $plugin;
		$this->money = new Money();
		$this->rank = new Rank($plugin);
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
			FormApi::makeCustomForm(function(Player $player, ?array $response) {
				if (!FormApi::formCancelled($response)) {
					switch ($response[0]) {
						case self::$ranks[0]:
							if ($this->money->reduceMoney($player->getName(), 1500000)) {
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
								$this->rank->setRank($player->getName(), 1);
							} else {
								$player->sendMessage(MessagesEnum::BUY_ERROR);
							}
							break;
						case self::$ranks[1]:
							if ($this->money->reduceMoney($player->getName(), 1000000)) {
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
								$this->rank->setRank($player->getName(), 2);
							} else {
								$player->sendMessage(MessagesEnum::BUY_ERROR);
							}
							break;
						case self::$ranks[2]:
							if ($this->money->reduceMoney($player->getName(), 700000)) {
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
								$this->rank->setRank($player->getName(), 3);
							} else {
								$player->sendMessage(MessagesEnum::BUY_ERROR);
							}
							break;
						case self::$ranks[3]:
							if ($this->money->reduceMoney($player->getName(), 500000)) {
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
								$this->rank->setRank($player->getName(), 4);
							} else {
								$player->sendMessage(MessagesEnum::BUY_ERROR);
							}
							break;
						case self::$ranks[4]:
							if ($this->money->reduceMoney($player->getName(), 300000)) {
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
								$this->rank->setRank($player->getName(), 5);
							} else {
								$player->sendMessage(MessagesEnum::BUY_ERROR);
							}
							break;
						case self::$ranks[5]:
							if ($this->money->reduceMoney($player->getName(), 100000)) {
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
								$this->rank->setRank($player->getName(), 6);
							} else {
								$player->sendMessage(MessagesEnum::BUY_ERROR);
							}
							break;
						case self::$ranks[6]:
							if ($this->money->reduceMoney($player->getName(), 50000)) {
								$player->sendMessage(MessagesEnum::BUY_SUCCESS);
								$this->rank->setRank($player->getName(), 7);
							} else {
								$player->sendMessage(MessagesEnum::BUY_ERROR);
							}
							break;
						default:
							$player->sendMessage("§7[§c失敗§7] §c購入をキャンセルしました。");
							break;
					}
				}
			})->setTitle("RankShop")
				->addElement(new Dropdown("§6V§bN§eCoin§rを消費してランクを買う事が出来ます。", self::$ranks))
				->addElement(new Label("§6V§bN  §61500000 §eCoin\n§5S  §61000000 §eCoin\n§eA  §6700000 §eCoin\n§cB  §6500000 §eCoin\n§aC  §6300000 §eCoin\n§3D  §6100000 §eCoin\n§7E  §650000 §eCoin"))
				->sendToPlayer($sender);
			return true;
		}
		$sender->sendMessage(TextFormat::RED . "このコマンドはプレイヤーのみが実行できます。");
		return true;
	}
}
