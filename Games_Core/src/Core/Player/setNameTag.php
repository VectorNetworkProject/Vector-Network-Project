<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/01
 * Time: 10:41
 */

namespace Core\Player;


use Core\Main;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\Player;

class setNameTag
{

	public static function set(Player $player, string $tag)
	{
		$remove = new RemoveEntityPacket();
		$remove->entityUniqueId = $player->getId();
		$packet = new AddPlayerPacket();
		$packet->uuid = $player->getUniqueId();
		$packet->username = $tag;
		$packet->entityRuntimeId = $player->getId();
		$packet->position = $player->asVector3();
		$packet->motion = $player->getMotion();
		$packet->yaw = $player->yaw;
		$packet->pitch = $player->pitch;
		$packet->item = $player->getInventory()->getItemInHand();
		$packet->metadata = $player->getDataPropertyManager()->getAll();
	}
}