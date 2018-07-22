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
        $rand = mt_rand(1, 7);
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
        }
    }
}
