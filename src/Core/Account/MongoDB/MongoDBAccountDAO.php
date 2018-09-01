<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 22:41
 */

namespace Core\Account\MongoDB;


use Core\Account\AbstractAccountDAO;
use Core\Account\AccountDTO;
use pocketmine\Server;
use pocketmine\utils\MainLogger;

class MongoDBAccountDAO extends AbstractAccountDAO
{
	public function __construct()
	{
		try {
			$mongo = new \Mongo();
			$this->db = $mongo->selectDB("");
			$this->db->selectCollection('accounts');
		} catch (\Exception $e) {
			MainLogger::getLogger()->logException($e);
			Server::getInstance()->getPluginManager()->getPlugin('')->setEnabled(false);
		}
	}

	public function getAccount(string $name): ?AccountDTO
	{
		$name = strtolower($name);
		$dto = null;

		try {
			$coll = $this->db->selectCollection(self::COLLECTION_NAME);
			$query = ['user_name' => $name];
			if (($doc = $coll->findOne($query)) !== null) {
				$dto = new AccountDTO($doc['_id'], $doc['user_name']);
			}
		} catch( \Exception $e ) {
			MainLogger::getLogger()->logException($e);
			Server::getInstance()->getPluginManager()->getPlugin('')->setEnabled(false);
		}

		return $dto;
	}

	public function registerAccount(string $name) : AccountDTO
	{
		$name = strtolower($name);
		$dto = null;

		try {
			$coll = $this->db->selectCollection(self::COLLECTION_NAME);
			$a = ['user_name' => $name];
			$coll->insert($a);
			$dto = new AccountDTO($a['_id'], $a['user_name']);
		} catch( \Exception $e ) {
			MainLogger::getLogger()->logException($e);
			Server::getInstance()->getPluginManager()->getPlugin('')->setEnabled(false);
		}

		return $dto;
	}
}