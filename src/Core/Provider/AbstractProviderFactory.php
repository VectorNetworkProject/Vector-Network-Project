<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 21:53
 */

namespace Core\Provider;


abstract class AbstractProviderFactory
{
	final public static function getInstance() : self
	{
		static $instance;
		return $instance = $instance ?: new static();
	}

	/** @var resource[] */
	protected $daos;

	abstract public function registerDAO( string $name, IDAO $dao );
	abstract public function getDAO( string $name );
}