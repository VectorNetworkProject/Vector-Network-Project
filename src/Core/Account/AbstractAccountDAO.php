<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 22:53
 */

namespace Core\Account;


use Core\Provider\MongoDB\IMongoDAO;

class AbstractAccountDAO implements IMongoDAO
{
	protected $db;
}