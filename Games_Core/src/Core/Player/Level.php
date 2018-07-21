<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/18
 * Time: 21:35
 */

namespace Core\Player;

use Core\DataFile;
use pocketmine\Player;
use pocketmine\Server;

class Level
{
    protected $server;
    public function __construct()
    {
        $this->server = Server::getInstance();
    }

    /**
     * @param string $name
     * @return int
     */
    public function getLevel(string $name) : int
    {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        return $data['networklevel'];
    }

    /**
     * @param string $name
     * @return int
     */
    public function getExp(string $name) : int
    {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        return $data['exp'];
    }

    /**
     * @param string $name
     * @return int
     */
    public function getMaxExp(string $name) : int
    {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        return $data['maxexp'];
    }

    /**
     * @param string $name
     * @param int $level
     */
    public function setLevel(string $name, int $level)
    {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        $data['networklevel'] = $level;
        $datafile->write('USERDATA', $data);
    }

    /**
     * @param string $name
     * @param int $exp
     */
    public function setExp(string $name, int $exp)
    {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        $data['exp'] = $exp;
        $datafile->write('USERDATA', $data);
    }

    /**
     * @param string $name
     * @param int $maxexp
     */
    public function setMaxExp(string $name, int $maxexp)
    {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        $data['maxexp'] = $maxexp;
        $datafile->write('USERDATA', $data);
    }

    /**
     * @param string $name
     * @param int $level
     */
    public function addLevel(string $name, int $level)
    {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        $data['networklevel'] += $level;
        $datafile->write('USERDATA', $data);
    }

    /**
     * @param string $name
     * @param int $exp
     */
    public function addExp(string $name, int $exp)
    {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        $data['exp'] += $exp;
        $datafile->write('USERDATA', $data);
    }

    /**
     * @param string $name
     * @param int $maxexp
     */
    public function addMaxExp(string $name, int $maxexp)
    {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        $data['maxexp'] += $maxexp;
        $datafile->write('USERDATA', $data);
    }

    /**
     * @param Player $player
     */
    public function LevelSystem(Player $player) {
        $exp = $this->getExp($player->getName());
        $maxexp = $this->getMaxExp($player->getName());
        if ($exp >= $maxexp) {
            $this->addMaxExp($player->getName(), 50);
            $this->addLevel($player->getName(), 1);
            $this->setExp($player->getName(), 0);
            $level = $this->getLevel($player->getName());
            $name = $player->getName();
            $this->server->broadcastMessage("§7[§b情報§7] $name が Lv.$level になりました。");
        } else {
            $rand = mt_rand(1, 10);
            $this->addExp($player->getName(), $rand);
            $player->sendMessage("§a+$rand EXP");
        }
    }
}
