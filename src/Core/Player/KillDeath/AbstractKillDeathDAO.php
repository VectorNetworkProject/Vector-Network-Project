<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/09/01
 * Time: 9:49
 */

namespace Core\Player\KillDeath;

use Core\Provider\IDAO;
use pocketmine\IPlayer;

abstract class AbstractKillDeathDAO implements IDAO
{
	public const COLLECTION_NAME = 'kill_death';
	protected $db;

	abstract public function getKillDeath(IPlayer $player): ?KillDeathDTO;
	abstract public function setKillDeath(KillDeathDTO $dto): void;
	abstract public function registerKillDeath(IPlayer $player, int $kill = 0, int $death = 0): KillDeathDTO;
}