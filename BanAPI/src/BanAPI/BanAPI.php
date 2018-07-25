<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/13
 * Time: 21:02
 */

namespace BanAPI;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class BanAPI extends PluginBase
{
	public function onEnable(): void
	{
		$this->getLogger()->info('BanAPIを読み込みました。');
	}

	/**
	 * @param Player $player
	 * @param string $reason
	 * @param string $by
	 * @param bool $isAdmin
	 */
	public function addBan(Player $player, string $reason, string $by, bool $isAdmin = true)
	{
		$player->kick($reason, $isAdmin);
		$this->getServer()->getIPBans()->addBan($player->getAddress(), $reason, null, $player->getName());
		$this->getServer()->getNameBans()->addBan($player, $reason, null, $player->getName());
		$this->getLogger()->info($player->getName() . "をBANしました。");
		$this->getServer()->broadcastMessage($player->getName() . " は $by によってBANされました。\n理由: $reason");
	}

	/**
	 * @param Player $player
	 */
	public function unBan(Player $player)
	{
		$this->getServer()->getIPBans()->remove($player);
		$this->getServer()->getNameBans()->remove($player);
		$this->getLogger()->info($player->getName() . "のBANを解除しました。");
	}
}
