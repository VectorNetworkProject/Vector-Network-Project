<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/31
 * Time: 22:42
 */

namespace Core\Account;


class AccountDTO
{
	/** @var string */
	private $id;
	/** @var string */
	private $name;

	public function __construct( string $id, string $name )
	{
		$this->id = $id;
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}
}