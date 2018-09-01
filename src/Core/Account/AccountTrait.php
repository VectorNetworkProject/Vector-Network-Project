<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/09/01
 * Time: 10:26
 */

namespace Core\Account;


use Core\Provider\DAOManagerFactory;
use pocketmine\IPlayer;
use pocketmine\Player;

trait AccountTrait
{
	/**
	 * @param Player $player
	 *
	 * @return AccountDTO
	 * @throws \Exception
	 */
	public function getAccount(IPlayer $player): AccountDTO
	{
		/** @var AbstractAccountDAO $dao */
		$dao = DAOManagerFactory::getDaoManager()->getDAO(AbstractAccountDAO::COLLECTION_NAME);
		$dto = null;
		if (($dto = $dao->getAccount($player->getName())) === null)
		{
			$dto = $dao->registerAccount($player->getName());
		}
		return $dto;
	}
}