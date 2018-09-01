<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 22:53
 */

namespace Core\Account;


use Core\Provider\IDAO;

class AbstractAccountDAO implements IDAO
{
	protected $db;
}