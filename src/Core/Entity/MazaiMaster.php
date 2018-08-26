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
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\Player;
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\FormApi;

class MazaiMaster extends EntityBase
{
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