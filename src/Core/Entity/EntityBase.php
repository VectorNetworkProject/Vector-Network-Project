<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/27
 * Time: 0:07
 */

namespace Core\Entity;

use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\utils\UUID;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\entity\Skin;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

//NOTICE NPCクラスを継承元にしたほうが使いやすいと思う
abstract class EntityBase
{
	protected static $players = [];

	public function __construct()
	{

	}

	/**
	 * @param Player $player
	 * @param string $username
	 * @param string $skin
	 * @param Vector3 $pos
	 * @param Item $item
	 * @param int $yaw
	 * @param int $headyaw
	 */
	public function Create(Player $player, string $username, string $skin, Vector3 $pos, Item $item, int $yaw = 0, int $headyaw = 0): void
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
		static::$players[$player->getName()] = $eid;
	}


	/**
	 * @param Player $player
	 */
	public function Remove(Player $player): void
	{
		if (isset(self::$players[$player->getName()])) {
			$eid = static::$players[$player->getName()];
			$removeEntityPacket = new RemoveEntityPacket();
			$removeEntityPacket->entityUniqueId = $eid;
			$player->sendDataPacket($removeEntityPacket);
			unset(static::$players[$player->getName()]);
		}
	}


	/**
	 * @param Player $player
	 * @return int
	 */
	public static function getEid(Player $player): int
	{
		if (isset(static::$players[$player->getName()])) {
			$eid = static::$players[$player->getName()];
			return $eid;
		} else {
			return 0;
		}
	}

	/**
	 * @param EntityLevelChangeEvent $event
	 * @return mixed
	 */
	abstract public function Check(EntityLevelChangeEvent $event): void;

	/**
	 * @param DataPacketReceiveEvent $event
	 * @return mixed
	 */
	abstract public function ClickEntity(DataPacketReceiveEvent $event): void;
}