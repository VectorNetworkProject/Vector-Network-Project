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

class FFAPvPCore
{
    public $worldname = "ffapvp";
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function AddDeathCount(Player $player)
    {
        if ($player->getLevel()->getName() === $this->worldname) {
            $datafile = new DataFile($player->getName());
            $data = $datafile->get('FFAPVP');
            $data['death'] += 1;
            $datafile->write('FFAPVP', $data);
            $player->addTitle("§cYou are dead", "§cあなたは死んでしまった", 20, 40, 20);
        }
    }
    public function AddKillCount(Player $player, string $name) {
        if ($player->getLevel()->getName() === $this->worldname) {
            $datafile = new DataFile($name);
            $data = $datafile->get('FFPAPVP');
            $data['kill'] += 1;
            $datafile->write('FFAPVP', $data);
        }
    }
}
