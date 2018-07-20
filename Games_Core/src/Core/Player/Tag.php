<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/20
 * Time: 16:46
 */

namespace Core\Player;


use Core\DataFile;
use pocketmine\Player;

class Tag
{
    /**
     * @param Player $player
     * @return mixed
     */
    public function getTag(Player $player)
    {
        $datafile = new DataFile($player->getName());
        $data = $datafile->get('USERDATA');
        return $data['tag'];
    }

    /**
     * @param Player $player
     * @param string $tag
     * @param int $colorid
     */
    public function setTag(Player $player, string $tag = "NoTag", int $colorid = 16)
    {
        $datafile = new DataFile($player->getName());
        $data = $datafile->get('USERDATA');
        switch ($colorid) {
            case 0:
                $data['tag'] = "§0$tag";
                break;
            case 1:
                $data['tag'] = "§1$tag";
                break;
            case 2:
                $data['tag'] = "§2$tag";
                break;
            case 3:
                $data['tag'] = "§3$tag";
                break;
            case 4:
                $data['tag'] = "§4$tag";
                break;
            case 5:
                $data['tag'] = "§5$tag";
                break;
            case 6:
                $data['tag'] = "§6$tag";
                break;
            case 7:
                $data['tag'] = "§7$tag";
                break;
            case 8:
                $data['tag'] = "§8$tag";
                break;
            case 9:
                $data['tag'] = "§9$tag";
                break;
            case 10:
                $data['tag'] = "§a$tag";
                break;
            case 11:
                $data['tag'] = "§b$tag";
                break;
            case 12:
                $data['tag'] = "§c$tag";
                break;
            case 13:
                $data['tag'] = "§d$tag";
                break;
            case 14:
                $data['tag'] = "§e$tag";
                break;
            case 15:
                $data['tag'] = "§f$tag";
                break;
            case 16:
                $data['tag'] = "$tag";
                break;
            default:
                $data['tag'] = "$tag";
                $player->sendMessage("§7[§cエラー§7] 指定したカラーIDが見つからなかった為デフォルトの色にしました。");
                break;
        }
        $datafile->write('USERDATA', $data);
        $usertag = $data['tag'];
        $player->sendMessage("§7[§a成功§7] §7あなたのタグを【 $usertag §7】に設定しました。");
    }
}