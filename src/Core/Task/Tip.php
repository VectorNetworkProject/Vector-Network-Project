<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/17
 * Time: 13:10
 */

namespace Core\Task;

use pocketmine\plugin\Plugin;

class Tip extends PluginTask
{
	public function __construct(Plugin $owner)
	{
		parent::__construct($owner);
	}

	public function onRun(int $currentTick)
	{
		$rand = mt_rand(1, 16);
		switch ($rand) {
			case 1:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7このゲームはまだ未完成です。");
				break;
			case 2:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7/statsで自分のステータスを確認出来ます。");
				break;
			case 3:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7/pingで応答速度を計測出来ます。");
				break;
			case 4:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7/rankshopでランクを買う事が出来ます。");
				break;
			case 5:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7/settagでタグを設定できます。");
				break;
			case 6:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7このサーバーはInkoHXとMazaiCraftyによって開発されています。");
				break;
			case 7:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7まだ開発中でありまだまだ機能追加を予定しています！！");
				break;
			case 8:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7運営チームのなりすましにご注意ください。");
				break;
			case 9:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7Hackerなどの報告はDiscordからお願いします。");
				break;
			case 10:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7/selectgameでゲームを選択できます。");
				break;
			case 11:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7よく過疎過疎うんこサーバーって言われます。");
				break;
			case 12:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7SpeedCorePvPは二つチームに分かれてコアを削り合うゲームだよ!!");
				break;
			case 13:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7FFAPvPはただのPvP");
				break;
			case 14:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7Survivalはサバイバルだよ自分の好きなようにできる事が可能です。(重いけどね)");
				break;
			case 15:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7Athleticはまだ開発中なんだ！！");
				break;
			case 16:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7この鯖には魔剤要素があります。");
				break;
			case 17:
				$this->owner->getServer()->broadcastMessage("§b[§7TIP§b] §7人が少ないときはサバイバルで遊びましょう()");
				break;
		}
	}
}
