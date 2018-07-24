<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/24
 * Time: 8:58
 */

namespace Core\Player;


use AntiCheat\Main;
use Core\DataFile;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;

class KillSound
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param string $name
     * @return int
     */
    public function getKillSound(string $name) : int {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        $killsoundId = $data['killsound'];
        return $killsoundId;
    }

    /**
     * @param Player $player
     * @param int $soundid
     */
    public function setKillSound(Player $player, int $soundid) {
        $datafile = new DataFile($player->getName());
        $data = $datafile->get('USERDATA');
        switch ($soundid) {
            case 1:
                $data['killsound'] = 1;
                break;
            case 2:
                $data['killsound'] = 2;
                break;
            default:
                $this->plugin->getLogger()->error("指定されたSoundIDが見つかりませんでした。");
                break;
        }
        $datafile->write('USERDATA', $data);
    }

    /**
     * @param Player $player
     * @param string $level
     */
    public function PlaySound(Player $player, string $level) {
        $datafile = new DataFile($player->getName());
        $data = $datafile->get('USERDATA');
        switch ($data['killsound']) {
            case 1:
                $this->plugin->getServer()->getLevelByName($level)->broadcastLevelSoundEvent(new Vector3($player->getFloorX(), $player->getY(), $player->getZ()), LevelSoundEventPacket::SOUND_RANDOM_ANVIL_USE);
                break;
            case 2:
                $this->plugin->getServer()->getLevelByName($level)->broadcastLevelSoundEvent(new Vector3($player->getFloorX(), $player->getY(), $player->getZ()), LevelSoundEventPacket::SOUND_FIRE);
                break;
        }
    }
}