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
		return $data['killsound'];
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
			case 2:
				$data['killsound'] = 2;
				break;
			case 3:
				$data['killsound'] = 3;
				break;
			case 4:
				$data['killsound'] = 4;
				break;
			case 5:
				$data['killsound'] = 5;
				break;
			case 6:
				$data['killsound'] = 6;
				break;
			case 7:
				$data['killsound'] = 7;
				break;
			case 8:
				$data['killsound'] = 8;
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
			case 2:
				$this->PlaySoundPacket($player, "music.1up", $player->getX(), $player->getY(), $player->getZ());
				break;
			case 3:
				$this->PlaySoundPacket($player, "music.guki", $player->getX(), $player->getY(), $player->getZ());
				break;
			case 4:
				$this->PlaySoundPacket($player, "music.dededon", $player->getX(), $player->getY(), $player->getZ());
				break;
			case 5:
				$this->PlaySoundPacket($player, "music.picyun", $player->getX(), $player->getY(), $player->getZ());
				break;
			case 6:
				$this->PlaySoundPacket($player, "music.busu", $player->getX(), $player->getY(), $player->getZ());
				break;
			case 7:
				$this->PlaySoundPacket($player, "music.musuka1", $player->getX(), $player->getY(), $player->getZ());
				break;
			case 8:
				$this->PlaySoundPacket($player, "music.musuka2", $player->getX(), $player->getY(), $player->getZ());
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
	 * @param float $pitch
	 */
	public function PlaySoundPacket(Player $player, string $soundname, int $x, int $y, int $z, int $volume = 1, float $pitch = 0.5)
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
