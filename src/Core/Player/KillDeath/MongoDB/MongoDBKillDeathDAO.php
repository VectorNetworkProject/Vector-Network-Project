<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/09/01
 * Time: 9:49
 */

namespace Core\Player\KillDeath\MongoDB;


use Core\Account\AccountTrait;
use Core\Player\KillDeath\AbstractKillDeathDAO;
use Core\Player\KillDeath\KillDeathDTO;
use Core\Provider\MongoDB\IMongoDAO;
use pocketmine\IPlayer;
use pocketmine\Server;
use pocketmine\utils\MainLogger;

class MongoDBKillDeathDAO extends AbstractKillDeathDAO implements IMongoDAO
{
	use AccountTrait;

	public function __construct()
	{
		try {
			$mongo = new \Mongo();
			$this->db = $mongo->selectDB("");
		} catch (\MongoConnectionException $e) {
			MainLogger::getLogger()->logException($e);
			Server::getInstance()->getPluginManager()->getPlugin('')->setEnabled(false);
		}
	}

	/**
	 * @param IPlayer $player
	 *
	 * @return KillDeathDTO|null
	 */
	public function getKillDeath(IPlayer $player): ?KillDeathDTO
	{
		$dto = null;

		try {
			$coll = $this->db->selectCollection(self::COLLECTION_NAME);
			$query = ['_id' => $this->getAccount($player)->getId()];
			$dto = null;
			if (($dto = $coll->findOne($query)) !== null) {
				$dto = new KillDeathDTO($query['_id'], $query['kill'], $query['death']);
			}
		} catch(\Exception $e) {
			MainLogger::getLogger()->logException($e);
			Server::getInstance()->getPluginManager()->getPlugin('')->setEnabled(false);
		}

		return $dto;
	}

	/**
	 * @param KillDeathDTO $dto
	 */
	public function setKillDeath(KillDeathDTO $dto): void
	{
		try {
			$coll = $this->db->selectCollection(self::COLLECTION_NAME);
			$criteria = ['_id' => $dto->getId()];
			$newobj = [
				'$set' =>
				[
					'kill' => $dto->getKill(),
					'death' => $dto->getDeath()
				]
			];
			$coll->update($criteria, $newobj);
		} catch(\Exception $e) {
			MainLogger::getLogger()->logException($e);
			Server::getInstance()->getPluginManager()->getPlugin('')->setEnabled(false);
		}
	}

	public function registerKillDeath(IPlayer $player, int $kill = 0, int $death = 0): KillDeathDTO
	{
		$dto = null;

		try {
			$coll = $this->db->selectCollection('kill_death');
			$query = [
				'_id' => $this->getAccount($player)->getId(),
				'kill' => $kill,
				'death' => $death
			];
			$coll->insert($query, ['w' => 1]);
			$dto = new KillDeathDTO($query['_id'], $query['kill'], $query['death']);
		} catch( \Exception $e ) {
			MainLogger::getLogger()->logException($e);
			Server::getInstance()->getPluginManager()->getPlugin('')->setEnabled(false);
		}

		return $dto;
	}
}