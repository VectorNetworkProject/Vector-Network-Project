<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/03
 * Time: 12:46
 */

namespace Core\Entity;

use Core\Game\Survival\SurvivalCore;
use Core\Main;
use Core\Task\Teleport\TeleportAthleticTask;
use Core\Task\Teleport\TeleportFFAPvPTask;
use Core\Task\Teleport\TeleportLobbyTask;
use Core\Task\Teleport\TeleportSpeedCorePvPTask;
use Core\Task\Teleport\TeleportSurvivalTask;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
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

class GameMaster
{
	protected static $players = [];
	protected $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	/**
	 * @param Player $player
	 * @param string $username
	 * @param Vector3 $pos
	 * @param Item $item
	 * @param int $yaw
	 * @param int $headyaw
	 */
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
		$player->sendDataPacket($addplayerpacket);
		for ($type = 0; $type <= 1; $type++) {
			$playerlistpacket = new PlayerListPacket();
			$playerlistpacket->entries[] = PlayerListEntry::createAdditionEntry($uuid, $eid, "", "", 0, new Skin("Standard_Custom", base64_decode(file_get_contents("plugins/Games_Core/resources/skins/GameMaster"))));
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
				$this->Create($entity, "§aGame§7Master", new Vector3(252, 4, 265), Item::get(Item::COMPASS, 0, 1));
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
							$level = $player->getLevel();
							switch ($key) {
								case 0:
									if ($level->getName() === "lobby") {
										$player->sendMessage("§c既にロビーに居ます。");
									} else {
										$player->sendMessage("§e10秒後テレポートします。");
										$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportLobbyTask($this->plugin, $player), 10 * 20);
									}
									break;
								case 1:
									if ($level->getName() === "ffapvp") {
										$player->sendMessage("§c既にFFAPvPに居ます");
									} else {
										if ($level->getName() === "lobby") {
											$player->teleport(new Position(254, 107, 254, $this->plugin->getServer()->getLevelByName("ffapvp")));
											$player->setSpawn(new Position(254, 107, 254, $this->plugin->getServer()->getLevelByName("ffapvp")));
											$player->sendMessage("§aテレポートしました。");
										} else {
											$player->sendMessage("§e10秒後テレポートします。");
											$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportFFAPvPTask($this->plugin, $player), 10 * 20);
										}
									}
									break;
								case 2:
									if ($level->getName() === "corepvp") {
										$player->sendMessage("§c既にCorePvPに居ます");
									} else {
										if ($level->getName() === "lobby") {
											$player->teleport(new Position(255, 8, 257, $this->plugin->getServer()->getLevelByName("corepvp")));
											$player->sendMessage("§aテレポートしました。");
										} else {
											$player->sendMessage("§e10秒後テレポートします。");
											$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportSpeedCorePvPTask($this->plugin, $player), 10 * 20);
										}
									}
									break;
								case 3:
									if ($player->isOp()) {
										if ($level->getName() === "athletic") {
											$player->sendMessage("§c既にAthleticに居ます");
										} else {
											if ($level->getName() === "lobby") {
												$player->teleport(new Position(254, 4, 254, $this->plugin->getServer()->getLevelByName("athletic")));
												$player->sendMessage("§aテレポートしました。");
											} else {
												$player->sendMessage("§e10秒後テレポートします。");
												$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportAthleticTask($this->plugin, $player), 10 * 20);
											}
										}
									} else {
										$player->sendMessage("現在開発者のみがテレポートする事が出来ます。");
									}
									break;
								case 4:
									FormApi::makeModalForm(function (Player $player, ?bool $bool) {
										if (!FormApi::formCancelled($bool)) {
											if ($bool) {
												$level = $player->getLevel();
												if ($level->getName() === "Survival") {
													$player->sendMessage("§c既にSurvivalに居ます");
												} else {
													if ($level->getName() === "lobby") {
														$player->teleport(new Position(225, 243, 256, $this->plugin->getServer()->getLevelByName("Survival")));
														$player->setSpawn(new Position(225, 243, 256, $this->plugin->getServer()->getLevelByName("Survival")));
														$player->sendMessage("§aテレポートしました。");
													} else {
														$player->sendMessage("§e10秒後テレポートします。");
														$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportSurvivalTask($this->plugin, $player), 10 * 20);
													}
												}
											}
										}
									})->setTitle("注意")
										->setContent("このゲームのステージはかなり重くアプリがクラッシュする事があります。\nそれでも参加したい方は諦めずに参加を繰り返して下さい。")
										->setButtonText(true, "俺の端末にクラッシュなんてねぇ")
										->setButtonText(false, "いや俺の端末はクソだから...")
										->sendToPlayer($player);
									break;
							}
						}
					})->setTitle("ゲーム選択")
						->setContent("遊びたいゲームを選択してください")
						->addButton(new Button("§eロビー"))
						->addButton(new Button("§6FFA§cPvP"))
						->addButton(new Button("§bSpeed§aCore§cPvP"))
						->addButton(new Button("§dAthletic"))
						->addButton(new Button("§aSurvival"))
						->sendToPlayer($player);
				}
			}
		}
	}
}