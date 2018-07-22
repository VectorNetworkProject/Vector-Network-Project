<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 13:48
 */

namespace Core\Player;

use Core\DataFile;
use Core\Main;
use pocketmine\Player;

class Rank
{
    protected $plugin;
    protected $tag;
    protected $level;

    /**
     * Rank constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->level = new Level($this->plugin);
        $this->tag = new Tag($this->plugin);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getRank(string $name)
    {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        $rank = $data['rank'];
        return $rank;
    }

    /**
     * @param Player $player
     * @param int $rankid
     */public function setRank(Player $player, int $rankid)
    {
        $datafile = new DataFile($player->getName());
        $data = $datafile->get('USERDATA');
        switch ($rankid) {
            case 1:
                $data['rank'] = "§6V§bN§r";
                break;
            case 2:
                $data['rank'] = "§5S§r";
                break;
            case 3:
                $data['rank'] = "§eA§r";
                break;
            case 4:
                $data['rank'] = "§cB§r";
                break;
            case 5:
                $data['rank'] = "§aC§r";
                break;
            case 6:
                $data['rank'] = "§3D§r";
                break;
            case 7:
                $data['rank'] = "§7E§r";
                break;
            default:
                $this->plugin->getLogger()->error("ランクIDが違います RankID: ".$rankid);
                break;
        }
        $datafile->write('USERDATA', $data);
        $name = $player->getName();
        $level = $this->level->getLevel($player->getName());
        $playerrank = $this->getRank($player->getName());
        $tag = $this->tag->getTag($player);
        $player->setNameTag("§7[§r $playerrank §7] §r$name");
        $player->setDisplayName("§7[§r $playerrank §7][ §rLv.$level §7][§r $tag §7] §r$name");
    }
}
