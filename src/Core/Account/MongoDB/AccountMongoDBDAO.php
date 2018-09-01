<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 22:41
 */

namespace Core\Account\MongoDB;


use Core\Account\AccountDTO;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\MainLogger;

class AccountMongoDBDAO
{
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

	public function createAccount(string $name): AccountDTO
	{
		$name = strtolower($name);

		try {
			$coll = $this->db->selectCollection(accounts);
			$query = ['user_name' => $name];
			$$coll->insert($query, ['w' => 1]);
			$dto = new AccountDTO();
			$dto->name = $query['user_name'];
			$dto->id = $query['_id'];
			return $dto;
		} catch( \Exception $e ) {
			MainLogger::getLogger()->logException($e);
			Server::getInstance()->getPluginManager()->getPlugin('')->setEnabled(false);
		}
	}
}