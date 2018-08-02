<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/02
 * Time: 16:54
 */

namespace Core\Entity;


use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\Player;
use pocketmine\utils\UUID;

class Mazai
{
	protected $eid;

	public function Create(Player $player, string $username, Vector3 $pos, Item $item, int $yaw = 0, int $headyaw = 0)
	{
		$addplayerpacket = new AddPlayerPacket();
		$addplayerpacket->uuid = ($uuid = UUID::fromRandom());
		$addplayerpacket->username = $username;
		$addplayerpacket->entityRuntimeId = ($eid = Entity::$entityCount++);
		$addplayerpacket->position = $pos;
		$addplayerpacket->yaw = $yaw;
		$addplayerpacket->headYaw = $headyaw;
		$addplayerpacket->item = $item;
		$flags = (
			(1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG) |
			(1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG) |
			(1 << Entity::DATA_FLAG_IMMOBILE)
		);
		$addplayerpacket->metadata = [
			Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
			Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $username]
		];
		$this->eid = $eid;
		$player->dataPacket($addplayerpacket);
		for ($type = 0; $type <= 1; $type++) {
			$playerlistpacket = new PlayerListPacket();
			$playerlistpacket->entries[] = PlayerListEntry::createAdditionEntry($uuid, $eid, "", "", 0, new Skin("Standard_Custom", hex2bin(file_get_contents("plugins/Games_Core/resources/skins/thinking"))));
			$playerlistpacket->type = $type;
			$player->dataPacket($playerlistpacket);
		}
	}

	public function Remove(Player $player)
	{
		$removeentitypacket = new RemoveEntityPacket();
		$removeentitypacket->entityUniqueId = $this->eid;
		$player->dataPacket($removeentitypacket);
	}

	public function getEid(): int
	{
		return $this->eid;
	}
}