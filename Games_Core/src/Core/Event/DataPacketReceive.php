<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 13:19
 */

namespace Core\Event;


use Core\Main;
use Core\Player\Money;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class DataPacketReceive
{
    protected $plugin, $money, $ok, $error;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->money = new Money();
        $this->ok = "§7[§a成功§7] §a購入に成功しました。";
        $this->error = "§7[§c失敗§7] §r§6V§bN§eCoin§rがたりません。";
    }
    public function event(DataPacketReceiveEvent $event) {
        $packet = $event->getPacket();
        $player = $event->getPlayer();
        if ($packet instanceof ModalFormResponsePacket) {
            if ($packet->formId === 45661984) {
                $data = json_decode($packet->formData, true);
                switch ($data) {
                    case '0':
                        if ($this->money->reduceMoney($player->getName(), 1500000)) {
                            $player->sendMessage($this->ok);
                            //書き込み
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case '1':
                        if ($this->money->reduceMoney($player->getName(), 1000000)) {
                            $player->sendMessage($this->ok);
                            //書き込み
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case '2':
                        if ($this->money->reduceMoney($player->getName(), 700000)) {
                            $player->sendMessage($this->ok);
                            //書き込み
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case '3':
                        if ($this->money->reduceMoney($player->getName(), 500000)) {
                            $player->sendMessage($this->ok);
                            //書き込み
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case '4':
                        if ($this->money->reduceMoney($player->getName(), 300000)) {
                            $player->sendMessage($this->ok);
                            //書き込み
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case '5':
                        if ($this->money->reduceMoney($player->getName(), 100000)) {
                            $player->sendMessage($this->ok);
                            //書き込み
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case '6':
                        if ($this->money->reduceMoney($player->getName(), 50000)) {
                            $player->sendMessage($this->ok);
                            //書き込み
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                }
            }
        }
    }
}