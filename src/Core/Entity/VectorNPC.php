<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/27
 * Time: 0:07
 */

namespace Core\Entity;

use pocketmine\entity\Human;
use pocketmine\entity\NPC;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\event\server\DataPacketReceiveEvent;

abstract class VectorNPC extends Human implements NPC
{
	/** @var callable */
	public $callable;

	public function __construct(Level $level, CompoundTag $nbt)
	{
		parent::__construct($level, $nbt);
	}

	public function setCallable(callable $callable): void
	{
		$this->callable = $callable;
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 * @return mixed
	 */
	final public function ClickEntity(DataPacketReceiveEvent $event): void
	{
		$this->callable( $event );
	}
}