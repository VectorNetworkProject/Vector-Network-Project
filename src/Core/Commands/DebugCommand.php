<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/22
 * Time: 11:40
 */

namespace Core\Commands;

use Core\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class DebugCommand extends PluginCommand
{
	protected $plugin;

	public function __construct(Main $plugin)
	{
		parent::__construct("debug", $plugin);
		$this->setPermission("vnp.command.debug");
		$this->setDescription("Admin Command");
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
		$x = $sender->getFloorX();
		$y = $sender->getFloorY();
		$z = $sender->getFloorZ();
		$levelname = $sender->getLevel()->getName();
		$itemid = $sender->getInventory()->getItemInHand()->getId();
		$meta = $sender->getInventory()->getItemInHand()->getDamage();
		$sender->sendMessage("X: $x\nY: $y\nZ: $z\nLevelName: $levelname\nBlockID: $itemid:$meta");
		return true;
	}
}
