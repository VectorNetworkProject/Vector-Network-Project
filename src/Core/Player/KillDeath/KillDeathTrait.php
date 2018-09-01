<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/09/01
 * Time: 10:01
 */

namespace Core\Player\KillDeath;


use Core\Provider\DAOManagerFactory;
use pocketmine\IPlayer;

trait KillDeathTrait
{
	/**
	 * @param IPlayer $player
	 *
	 * @return KillDeathDTO
	 * @throws \Exception
	 */
	public function getKillDeath(IPlayer $player): KillDeathDTO
	{
		/** @var AbstractKillDeathDAO $dao */
		$dao = DAOManagerFactory::getDaoManager()->getDAO(AbstractKillDeathDAO::COLLECTION_NAME);
		if (($dto = $dao->getKillDeath($player)) === null) {
			$dto = $dao->registerKillDeath($player);
		}
		return $dto;
	}

	/**
	 * @param IPlayer $player
	 * @param int     $deltaKill
	 *
	 * @throws \Exception
	 */
	public function addKill(IPlayer $player, $deltaKill = 1): void
	{
		/** @var AbstractKillDeathDAO $dao */
		$dao = DAOManagerFactory::getDaoManager()->getDAO(AbstractKillDeathDAO::COLLECTION_NAME);
		$dto = $this->getKillDeath($player);
		$dto->setKill($dto->getKill() + $deltaKill);
		$dao->setKillDeath($dto);
	}

	/**
	 * @param IPlayer $player
	 * @param int     $deltaDeath
	 *
	 * @throws \Exception
	 */
	public function addDeath(IPlayer $player, $deltaDeath = 1): void
	{
		/** @var AbstractKillDeathDAO $dao */
		$dao = DAOManagerFactory::getDaoManager()->getDAO(AbstractKillDeathDAO::COLLECTION_NAME);
		$dto = $this->getKillDeath($player);
		$dto->setKill($dto->getKill() + $deltaDeath);
		$dao->setKillDeath($dto);
	}
}