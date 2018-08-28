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
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\utils\UUID;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\entity\Skin;
use pocketmine\event\server\DataPacketReceiveEvent;

abstract class VectorNPC extends Human implements NPC
{
	protected static $players = [];

	public function __construct(Level $level, CompoundTag $nbt)
	{
		parent::__construct($level, $nbt);
	}

	/**
	 * @param Player $player
	 * @param string $username
	 * @param string $skin
	 * @param Vector3 $pos
	 * @param Item $item
	 * @param int $id
	 * @param int $yaw
	 * @param int $headyaw
	 */
	public function Create(Player $player, string $username, string $skin, Vector3 $pos, Item $item, int $id, int $yaw = 0, int $headyaw = 0): void
	{
		$addPlayerPacket = new AddPlayerPacket();
		$addPlayerPacket->uuid = ($uuid = UUID::fromRandom());
		$addPlayerPacket->username = $username;
		$addPlayerPacket->entityRuntimeId = ($eid = Entity::$entityCount++);
		$addPlayerPacket->position = $pos;
		$addPlayerPacket->yaw = $yaw;
		$addPlayerPacket->headYaw = $headyaw;
		$addPlayerPacket->item = $item;
		$flags = (
			(1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG) |
			(1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG) |
			(1 << Entity::DATA_FLAG_IMMOBILE)
		);
		$addPlayerPacket->metadata = [
			Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
			Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $username]
		];
		$player->sendDataPacket($addPlayerPacket);
		for ($type = 0; $type <= 1; $type++) {
			$playerListPacket = new PlayerListPacket();
			$playerListPacket->entries[] = PlayerListEntry::createAdditionEntry
			(
				$uuid,
				$eid,
				"",
				"",
				0,
				new Skin("Standard_Custom", base64_decode(file_get_contents("plugins/Games_Core/resources/skins/$skin")))
			);
			$playerListPacket->type = $type;
			$player->sendDataPacket($playerListPacket);
		}
		static::$players[$player->getName()][$id] = $eid;
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 * @return mixed
	 */
	abstract public function ClickEntity(DataPacketReceiveEvent $event): void;
}