<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/08
 * Time: 17:03
 */

namespace Core\Commands;


use Core\Player\Tag;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;

class adtag extends PluginCommand
{
	protected $tag;

	public function __construct(Plugin $plugin)
	{
		parent::__construct("adtag", $plugin);
		$this->setDescription("Admin Command");
		$this->setPermission("vector.network.admin");
		$this->tag = new Tag();
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$this->getPlugin()->isEnabled()) {
			return false;
		}

		if (!$this->testPermission($sender)) {
			return false;
		}

		if (!isset($args[0]) || !isset($args[0])) {
			return false;
		}
		foreach ($this->getPlugin()->getServer()->getOnlinePlayers() as $player) {
			if ($player->getName() === $args[0]) {
				$this->tag->setTag($player);
			}
		}
		return true;
	}

	public function getPlugin(): Plugin
	{
		return parent::getPlugin();
	}
}