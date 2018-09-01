<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 22:53
 */

namespace Core\Account;


use Core\Provider\IDAO;

abstract class AbstractAccountDAO implements IDAO
{
	public const COLLECTION_NAME = 'accounts';
	protected $db;
	abstract public function getAccount(string $name): ?AccountDTO;
	abstract public function registerAccount(string $name): AccountDTO;
}