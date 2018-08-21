<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/05
 * Time: 14:21
 */

namespace Core\Entity;


use Core\Commands\MessagesEnum;
use Core\Main;
use Core\Player\Level;
use Core\Player\MazaiPoint;
use Core\Player\Money;
use Core\Task\LevelCheckingTask;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\Player;
use pocketmine\utils\UUID;
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\FormApi;

class MazaiMaster
{
	private static $players = [];
	private $money;
	private $level;
	private $mazai;

	public function __construct()
	{
		$this->money = new Money();
		$this->level = new Level();
		$this->mazai = new MazaiPoint();
	}

	/**
	 * @param Player $player
	 * @param string $username
	 * @param Vector3 $pos
	 * @param Item $item
	 * @param int $yaw
	 * @param int $headyaw
	 */
	public function Create(Player $player, string $username, Vector3 $pos, Item $item, int $yaw = 105, int $headyaw = 105)
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
		$player->sendDataPacket($addplayerpacket);
		for ($type = 0; $type <= 1; $type++) {
			$playerlistpacket = new PlayerListPacket();
			$playerlistpacket->entries[] = PlayerListEntry::createAdditionEntry($uuid, $eid, "", "", 0, new Skin("Standard_Custom", base64_decode(file_get_contents("plugins/Games_Core/resources/skins/MazaiNPC"))));
			$playerlistpacket->type = $type;
			$player->sendDataPacket($playerlistpacket);
		}
		self::$players[$player->getName()] = $eid;
	}

	/**
	 * @param Player $player
	 */
	public function Remove(Player $player)
	{
		if (isset(self::$players[$player->getName()])) {
			$eid = self::$players[$player->getName()];
			$removeentitypacket = new RemoveEntityPacket();
			$removeentitypacket->entityUniqueId = $eid;
			$player->sendDataPacket($removeentitypacket);
			unset(self::$players[$player->getName()]);
		}
	}


	/**
	 * @param Player $player
	 * @return int
	 */
	public static function getEid(Player $player): int
	{
		if (isset(self::$players[$player->getName()])) {
			$eid = self::$players[$player->getName()];
			return $eid;
		} else {
			return 0;
		}
	}

	/**
	 * @param EntityLevelChangeEvent $event
	 */
	public function Check(EntityLevelChangeEvent $event)
	{
		$entity = $event->getEntity();
		if ($entity instanceof Player) {
			if ($event->getTarget()->getName() === 'lobby') {
				$this->Create($entity, "§a魔剤§7マスター", new Vector3(287, 10, 270), Item::get(Item::POTION, 11, 1));
			} else {
				$this->Remove($entity);
			}
		}
	}

	public function ClickEntity(DataPacketReceiveEvent $event)
	{
		$packet = $event->getPacket();
		$player = $event->getPlayer();
		if ($packet instanceof InventoryTransactionPacket) {
			if ($packet->transactionType === $packet::TYPE_USE_ITEM_ON_ENTITY) {
				if ($packet->trData->entityRuntimeId === self::getEid($player)) {
					FormApi::makeListForm(function (Player $player, ?int $key) {
						if (!FormApi::formCancelled($key)) {
							switch ($key) {
								case 0:
									if ($this->mazai->reduceMazai($player->getName(), 1)) {
										$player->sendMessage(MessagesEnum::MAZAI_SUCCESS);
										$this->level->addExp($player->getName(), 300);
										Main::$instance->getScheduler()->scheduleDelayedTask(new LevelCheckingTask(Main::$instance, $player), 20);
									} else {
										$player->sendMessage(MessagesEnum::MAZAI_ERROR);
									}
									break;
								case 1:
									if ($this->mazai->reduceMazai($player->getName(), 1)) {
										$player->sendMessage(MessagesEnum::MAZAI_SUCCESS);
										$this->money->addMoney($player->getName(), 10000);
									} else {
										$player->sendMessage(MessagesEnum::MAZAI_ERROR);
									}
									break;
							}
						}
					})->setTitle("§aMAZAI§e変換所")
						->setContent("§aMAZAI§rを色んなものに変換します。")
						->addButton(new Button("§e300§aXP\n§e1§aMAZAI"))
						->addButton(new Button("§e10000§6V§bN§eCoin\n§e1§aMAZAI\""))
						->sendToPlayer($player);
				}
			}
		}
	}
}