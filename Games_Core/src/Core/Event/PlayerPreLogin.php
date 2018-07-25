<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 15:03
 */

namespace Core\Event;

use Core\Main;
use pocketmine\event\player\PlayerPreLoginEvent;

class PlayerPreLogin
{
	protected $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function event(PlayerPreLoginEvent $event)
	{
		$player = $event->getPlayer();
		if ($this->plugin->getServer()->hasWhitelist()) {
			if (!$this->plugin->getServer()->isWhitelisted(strtolower($player->getName()))) {
				$player->close($player->getLeaveMessage(), "     §6Vector §bNetwork\n§7現在ホワイトリストです。\n§7詳しい情報はLobiまたはDiscordから得ることができます。\n§7Discord: https://hxbot.tk/VNP-D\n§7Lobi: https://hxbot.tk/VNP-L");
			}
		}
	}
}
