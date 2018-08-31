<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 22:47
 */

namespace Core\Provider\MongoDB;


use Core\Provider\AbstractProviderFactory;
use Core\Provider\IDAO;
use pocketmine\utils\Utils;

class MongoDBProviderFactory extends AbstractProviderFactory
{
	public function registerDAO( string $name, string $className )
	{
		Utils::testValidInstance( $className, self::class );
		// TODO: Implement registerDAO() method.
	}

	public function createAccountDAO( string $name )
	{
		// TODO: Implement createAccountDAO() method.
	}
}