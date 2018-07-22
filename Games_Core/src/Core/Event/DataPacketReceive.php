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
use Core\Player\Rank;
use Core\Player\Tag;
use Core\Task\Teleport\TeleportFFAPvPTask;
use Core\Task\Teleport\TeleportLobbyTask;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class DataPacketReceive
{
    protected $plugin;
    protected $money;
    protected $ok;
    protected $error;
    protected $rank;
    protected $tag;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->money = new Money();
        $this->rank = new Rank($this->plugin);
        $this->tag = new Tag();
        $this->ok = "§7[§a成功§7] §a購入に成功しました。";
        $this->error = "§7[§c失敗§7] §r§6V§bN§eCoin§rがたりません。";
    }
    public function event(DataPacketReceiveEvent $event)
    {
        $packet = $event->getPacket();
        $player = $event->getPlayer();
        if ($packet instanceof ModalFormResponsePacket) {
            if ($packet->formId === 45661984) {
                $data = json_decode($packet->formData, true);
                switch ($data[0]) {
                    case 0:
                        if ($this->money->reduceMoney($player->getName(), 1500000)) {
                            $player->sendMessage($this->ok);
                            $this->rank->setRank($player->getName(), 1);
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case 1:
                        if ($this->money->reduceMoney($player->getName(), 1000000)) {
                            $player->sendMessage($this->ok);
                            $this->rank->setRank($player->getName(), 2);
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case 2:
                        if ($this->money->reduceMoney($player->getName(), 700000)) {
                            $player->sendMessage($this->ok);
                            $this->rank->setRank($player->getName(), 3);
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case 3:
                        if ($this->money->reduceMoney($player->getName(), 500000)) {
                            $player->sendMessage($this->ok);
                            $this->rank->setRank($player->getName(), 4);
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case 4:
                        if ($this->money->reduceMoney($player->getName(), 300000)) {
                            $player->sendMessage($this->ok);
                            $this->rank->setRank($player->getName(), 5);
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case 5:
                        if ($this->money->reduceMoney($player->getName(), 100000)) {
                            $player->sendMessage($this->ok);
                            $this->rank->setRank($player->getName(), 6);
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    case 6:
                        if ($this->money->reduceMoney($player->getName(), 50000)) {
                            $player->sendMessage($this->ok);
                            $this->rank->setRank($player->getName(), 7);
                        } else {
                            $player->sendMessage($this->error);
                        }
                        break;
                    default:
                        $player->sendMessage("§7[§c失敗§7] §c購入をキャンセルしました。");
                        break;
                }
            }
            if ($packet->formId === 8489612) {
                $data = json_decode($packet->formData, true);
                if (empty($data)) {
                    $player->sendMessage("§7[§c失敗§7] §cタグ名を記入して下さい。");
                } else {
                    if (empty($data[2])) {
                        $tag = "NoTag";
                    } else {
                        $tag = $data[2];
                    }
                    if ($this->money->reduceMoney($player->getName(), 1000)) {
                        $player->sendMessage("§7[§b情報§7] §6V§bN§eCoin§7を§61000§7消費しました。");
                        $this->tag->setTag($player, $tag, $data[1]);
                    } else {
                        $player->sendMessage($this->error);
                    }
                }
            }
            if ($packet->formId === 45786154) {
                $data = json_decode($packet->formData, true);
                switch ($data[0]) {
                    case 0:
                        if ($player->getLevel()->getName() === "lobby") {
                            $player->sendMessage("§c既にロビーに居ます。");
                        } else {
                            $player->sendMessage("§e10秒後テレポートします。");
                            $this->plugin->getScheduler()->scheduleDelayedTask(new TeleportLobbyTask($this->plugin, $player), 10*20);
                        }
                        break;
                    case 1:
                        if ($player->getLevel()->getName() === "ffapvp") {
                            $player->sendMessage("§c既にFFAPvPに居ます");
                        } else {
                            if ($player->getLevel()->getName() === "lobby") {
                                $player->teleport(new Position(254, 107, 254, $this->plugin->getServer()->getLevelByName("ffapvp")));
                                $player->sendMessage("§aテレポートしました。");
                            } else {
                                $player->sendMessage("§e10秒後テレポートします。");
                                $this->plugin->getScheduler()->scheduleDelayedTask(new TeleportFFAPvPTask($this->plugin, $player), 10*20);
                            }
                        }
                        break;
                }
            }
        }
    }
}
