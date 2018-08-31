<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 21:55
 */

namespace Core\AccountProvider\MongoDB;


use Core\AccountProvider\AbstractAccountProviderFactory;
use pocketmine\Server;

class MongoDBAccountProviderFactory extends AbstractAccountProviderFactory
{
	protected $plugin;

	protected function __construct()
	{
		$this->plugin = Server::getInstance()->getPluginManager()->getPlugin( "FileStore" );
	}



	public function createAccountDAO()
	{

	}
}