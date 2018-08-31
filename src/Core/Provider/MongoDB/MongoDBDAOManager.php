<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 22:47
 */

namespace Core\Provider\MongoDB;


use Core\Provider\AbstractDAOManager;
use pocketmine\utils\Utils;

class MongoDBDAOManager extends AbstractDAOManager
{
	public function registerDAO( string $name, string $className ): void
	{
		Utils::testValidInstance( $className, self::class );

		if (isset($this->daos[$name])) {
			throw new \InvalidArgumentException("Instance $name has already been registered");
		}

		$this->daos[$name] = new $className();
	}
}