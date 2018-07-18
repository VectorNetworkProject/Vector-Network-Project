<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/14
 * Time: 16:58
 */

namespace Core\Game\Survival;

use Core\DataFile;
use Core\Main;
use pocketmine\Player;

class SurvivalCore
{
    public $worldname = "Survival";
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function AddDeathCount(Player $player)
    {
        if ($player->getLevel()->getName() === $this->worldname) {
            $datafile = new DataFile($player->getName());
            $data = $datafile->get('survival');
            $data['death'] += 1;
            $datafile->write('survival', $data);
            $player->addTitle("§cYou are dead", "§cあなたは死んでしまった", 20, 40, 20);
        }
    }
    public function AddKillCount(Player $player) {
        if ($player->getLevel()->getName() === $this->worldname) {
            $datafile = new DataFile($player->getName());
            $data = $datafile->get('survival');
            $data['kill'] += 1;
            $datafile->write('survival', $data);
        }
    }
    public function AddBreakCount(Player $player) {
        if ($player->getLevel()->getName() === $this->worldname) {
            $datafile = new DataFile($player->getName());
            $data = $datafile->get('survival');
            $data['break'] += 1;
            $datafile->write('survival', $data);
        }
    }
    public function AddPlaceCount(Player $player) {
        if ($player->getLevel()->getName() === $this->worldname) {
            $datafile = new DataFile($player->getName());
            $data = $datafile->get('survival');
            $data['place'] += 1;
            $datafile->write('survival', $data);
        }
    }
}
