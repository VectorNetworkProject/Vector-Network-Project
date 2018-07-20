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

    const BLACK = 0;
    const DARK_BLUE = 1;
    const DARK_GREEN = 2;
    const DARK_AQUA = 3;
    const DARK_RED = 4;
    const PURPLE = 5;
    const GOLD = 6;
    const GRAY = 7;
    const DARK_GRAY = 8;
    const BLUE = 9;
    const LIGHT_GREEN = 10;
    const AQUA = 11;
    const RED = 12;
    const PINK = 13;
    const YELLOW = 14;
    const WHITE = 15;
    const NO_COLLOR = 16;

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
        if (mb_strlen($tag) >= 9){
            $player->sendMessage("§7[§c失敗§7] §cタグは8文字以内にして下さい");
            return;
        }
        switch ($colorid) {
            case Tag::BLACK:
                $data['tag'] = "§0$tag";
                break;
            case Tag::DARK_BLUE:
                $data['tag'] = "§1$tag";
                break;
            case Tag::DARK_GREEN:
                $data['tag'] = "§2$tag";
                break;
            case Tag::DARK_AQUA:
                $data['tag'] = "§3$tag";
                break;
            case Tag::DARK_RED:
                $data['tag'] = "§4$tag";
                break;
            case Tag::PURPLE:
                $data['tag'] = "§5$tag";
                break;
            case Tag::GOLD:
                $data['tag'] = "§6$tag";
                break;
            case Tag::GRAY:
                $data['tag'] = "§7$tag";
                break;
            case Tag::DARK_GRAY:
                $data['tag'] = "§8$tag";
                break;
            case Tag::BLUE:
                $data['tag'] = "§9$tag";
                break;
            case Tag::LIGHT_GREEN:
                $data['tag'] = "§a$tag";
                break;
            case Tag::AQUA:
                $data['tag'] = "§b$tag";
                break;
            case Tag::RED:
                $data['tag'] = "§c$tag";
                break;
            case Tag::PINK:
                $data['tag'] = "§d$tag";
                break;
            case Tag::YELLOW:
                $data['tag'] = "§e$tag";
                break;
            case Tag::WHITE:
                $data['tag'] = "§f$tag";
                break;
            case Tag::NO_COLLOR:
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
