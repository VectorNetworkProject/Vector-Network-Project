<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 22:47
 */

namespace Core\Provider\MongoDB;


use Core\Provider\AbstractProviderFactory;
use pocketmine\utils\Utils;

class MongoDBProviderFactory extends AbstractProviderFactory
{
	public function registerDAO( string $name, string $className )
	{
		Utils::testValidInstance( $className, self::class );

		if (isset($this->daos[$name])) {
			throw new \InvalidArgumentException("Instance $name has already been registered");
		}

		$this->daos[$name] = new $className();
	}

	public function createAccountDAO( string $name )
	{
		// TODO: Implement createAccountDAO() method.
	}
}