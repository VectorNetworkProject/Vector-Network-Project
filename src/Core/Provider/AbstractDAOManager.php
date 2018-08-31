<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 21:53
 */

namespace Core\Provider;


abstract class AbstractDAOManager
{
	final public static function getInstance() : self
	{
		static $instance;
		return $instance = $instance ?: new static();
	}

	/** @var IDAO[] */
	protected $daos;

	/**
	 * @param string $name
	 * @param string $className
	 *
	 * @return mixed
	 */
	abstract public function registerDAO( string $name, string $className ): void;

	/**
	 * @param string $name
	 *
	 * @return IDAO
	 * @throws \Exception
	 */
	public final function getDAO( string $name ) : IDAO
	{
		if (!isset($this->$daos[$name])) {
			throw new \InvalidArgumentException( "Instance $name does not exist" );
		}

		return $this->daos[$name];
	}
}