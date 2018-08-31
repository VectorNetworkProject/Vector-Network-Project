<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 23:46
 */

namespace Core\Provider;


use Core\Provider\MongoDB\MongoDBDAOManager;

class DAOManagerFactory
{
	public static function getDaoManager()
	{
		static $manager;
		return $manager = $manager ?: new MongoDBDAOManager();
	}
}