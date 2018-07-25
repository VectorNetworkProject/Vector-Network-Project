<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/24
 * Time: 8:58
 */

namespace Core\Player;

use Core\DataFile;
use Core\Main;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
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
	public function getKillSound(string $name): int
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('USERDATA');
		$killsoundId = $data['killsound'];
		return $killsoundId;
	}

	/**
	 * @param Player $player
	 * @param int $soundid
	 */
	public function setKillSound(Player $player, int $soundid)
	{
		$datafile = new DataFile($player->getName());
		$data = $datafile->get('USERDATA');
		switch ($soundid) {
			case 0:
				$data['killsound'] = 0;
				break;
			case 1:
				$data['killsound'] = 1;
				break;
			default:
				$this->plugin->getLogger()->error("指定されたSoundIDが見つかりませんでした。");
				break;
		}
		$datafile->write('USERDATA', $data);
	}

	/**
	 * @param Player $player
	 */
	public function PlaySound(Player $player)
	{
		$datafile = new DataFile($player->getName());
		$data = $datafile->get('USERDATA');
		switch ($data['killsound']) {
			case 0:
				return;
				break;
			case 1:
				$this->PlaySoundPacket($player, "music.tin", $player->getX(), $player->getY(), $player->getZ());
				break;
		}
	}

	/**
	 * @param Player $player
	 * @param string $soundname
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $volume
	 * @param int $pitch
	 */
	public function PlaySoundPacket(Player $player, string $soundname, int $x, int $y, int $z, int $volume = 20, int $pitch = 1)
	{
		$sound = new PlaySoundPacket();
		$sound->soundName = $soundname;
		$sound->volume = $volume;
		$sound->pitch = $pitch;
		$sound->x = $x;
		$sound->y = $y;
		$sound->z = $z;
		$player->dataPacket($sound);
	}
}
