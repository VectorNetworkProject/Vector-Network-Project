<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 21:53
 */

namespace Core\AccountProvider;


abstract class AbstractAccountProviderFactory
{
	final public static function getInstance() : self
	{
		static $instance;
		return $instance = $instance ?: new static();
	}

	abstract public function createAccountDAO();
}