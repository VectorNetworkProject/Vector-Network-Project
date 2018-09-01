<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/09/01
 * Time: 9:50
 */

namespace Core\Player\KillDeath;


class KillDeathDTO
{
	/**
	 * KillDeathDTO constructor.
	 *
	 * @param int $id
	 * @param int $kill
	 * @param int $death
	 */
	public function __construct( int $id, int $kill, int $death  )
	{
		$this->id = $id;
		$this->kill = $kill;
		$this->death = $death;
	}

	/** @var string */
	private $id;
	/** @var int */
	private $kill;
	/** @var int */
	private $death;

	/**
	 * @return string
	 */
	public function getId() : string
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getKill() : int
	{
		return $this->kill;
	}

	/**
	 * @param int $kill
	 */
	public function setKill( int $kill ) : void
	{
		$this->kill = $kill;
	}

	/**
	 * @return int
	 */
	public function getDeath() : int
	{
		return $this->death;
	}

	/**
	 * @param int $death
	 */
	public function setDeath( int $death ) : void
	{
		$this->death = $death;
	}
}