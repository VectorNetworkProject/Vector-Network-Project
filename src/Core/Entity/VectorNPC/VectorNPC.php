<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/27
 * Time: 0:07
 */

namespace Core\Entity\VectorNPC;

use Core\Player\MazaiPoint;
use Core\Player\Money;
use pocketmine\entity\Human;
use pocketmine\entity\NPC;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Level;
use Core\Player\Level as VLevel;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;

class VectorNPC extends Human implements NPC
{
	/** @var callable */
	public $callable;

	protected $money;
	protected $vLevel;
	protected $mazai;

	public function __construct(Level $level, CompoundTag $nbt)
	{
		parent::__construct($level, $nbt);
		$this->money = new Money();
		$this->vLevel = new VLevel();
		$this->mazai = new MazaiPoint();
	}

	public function setCallable(callable $callable): void
	{
		$this->callable = $callable;
	}

	/**
	 * @return Money
	 */
	public function getMoney(): Money
	{
		return $this->money;
	}

	/**
	 * @return MazaiPoint
	 */
	public function getMazai(): MazaiPoint
	{
		return $this->mazai;
	}

	/**
	 * @return \Core\Player\Level
	 */
	public function getVLevel(): VLevel
	{
		return $this->vLevel;
	}

	public function attack(EntityDamageEvent $source): void
	{
		if (!$source instanceof EntityDamageByEntityEvent) {
			parent::attack($source);
		} else {
			if (($player = $source->getEntity()) instanceof Player) {
				$callable = $this->callable;
				$callable( $player );
			}
		}
	}
}