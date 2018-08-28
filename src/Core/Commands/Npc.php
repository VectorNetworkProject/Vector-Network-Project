<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/28
 * Time: 19:37
 */

namespace Core\Commands;


use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\Plugin;

class Npc extends PluginCommand
{
	public function __construct(Plugin $owner)
	{
		parent::__construct("npc", $owner);
		$this->setPermission("vector.network.admin");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		$
	}
}