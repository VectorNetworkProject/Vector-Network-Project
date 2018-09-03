<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/18
 * Time: 10:37
 */

namespace Core\Task;

use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Internet;
use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\FormApi;

class JoinTitle extends PluginTask
{
	protected $player;

	/**
	 * JoinTitle constructor.
	 * @param Plugin $plugin
	 * @param Player $player
	 */
	public function __construct(Plugin $plugin, Player $player)
	{
		parent::__construct($plugin);
		$this->player = $player;
	}

	/**
	 * Actions to execute when run
	 *
	 * @param int $currentTick
	 *
	 * @return void
	 */
	public function onRun(int $currentTick)
	{
		$player = $this->player;
		$player->addTitle("§6Vector §bNetwork", "§eDeveloped by InkoHX MazaiCrafty", 40, 100, 40);
		$player->sendMessage("§a---===< §6Vector §bNetwork §eProject §a>===---\n§bDeveloped by InkoHX MazaiCrafty\n§bGitHub: §7https://github.com/InkoHX/Vector-Network-Project\n§bTwitter: §7https://twitter.com/InkoHX\n§9Discord: §7https://discord.gg/EF2G5dh\n§a---=============================---");
		$custom = FormApi::makeCustomForm(function ($response) {
			if (!FormApi::formCancelled($response)) {
			}
		});
		$custom->setTitle("変更内容")
			->addElement(new Label("ゲームバージョン: " . self::getVersion() . "\n" . self::getChange()))
			->setId(mt_rand(111111, 999999))
			->sendToPlayer($player);
	}

	/**
	 * @return bool|mixed
	 */
	private static function getChange()
	{
		return Internet::getURL('https://vnp.inkohx.xyz/log/change.txt');
	}

	/**
	 * @return bool|mixed
	 */
	private static function getVersion()
	{
		return Internet::getURL('https://vnp.inkohx.xyz/log/game_version.txt');
	}
}
