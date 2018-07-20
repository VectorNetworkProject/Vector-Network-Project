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

class Rank
{
    protected $plugin;

    /**
     * Rank constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
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
     * @param string $name
     * @param int $rankid
     */
    public function setRank(string $name, int $rankid) {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        switch ($rankid) {
            case 1:
                $data['rank'] = "§6V§bN§r";
                break;
            case 2:
                $data['rank'] = "§5S§r";
                break;
            case 3:
                $data['rank'] = "§6A§r";
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
    }
}