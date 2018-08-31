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

class MongoDBProviderFactory extends AbstractProviderFactory
{
	public function createAccountDAO( string $name )
	{
		// TODO: Implement createAccountDAO() method.
	}

	public function registerDAO( string $name, IMongoDAO $dao )
	{
		// TODO: Implement registerDAO() method.
	}


}