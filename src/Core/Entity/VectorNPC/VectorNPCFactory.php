<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/28
 * Time: 16:47
 */

namespace Core\Entity\VectorNPC;


use Core\Commands\MessagesEnum;
use Core\Entity\LevelNotFoundException;
use Core\Main;
use Core\Task\LevelCheckingTask;
use Core\Task\Teleport\TeleportAthleticTask;
use Core\Task\Teleport\TeleportFFAPvPTask;
use Core\Task\Teleport\TeleportLobbyTask;
use Core\Task\Teleport\TeleportSpeedCorePvPTask;
use Core\Task\Teleport\TeleportSurvivalTask;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\FormApi;

class VectorNPCFactory
{
	/** @var Main */
	private $plugin;
	public function createGameMaster(): VectorNPC
	{
		if (($level = Server::getInstance()->getLevelByName("lobby")) === null )
		{
			throw new LevelNotFoundException("Fould not found lobby");
		}
		$nbt = Entity::createBaseNBT(new Vector3(260, 4, 265));
		/** @var $gameMaster VectorNPC */
		$gameMaster = Entity::createEntity( "Human", $level, $nbt );
		$item = Item::get(Item::POTION, 11, 1);
		$gameMaster->getInventory()->setItemInHand($item);
		$skin = new Skin("Standard_Custom", base64_decode(file_get_contents("plugins/Games_Core/resources/skins/GameMaster")));
		$gameMaster->setSkin($skin);
		$gameMaster->setCallable
		(
			function(Player $player) use($gameMaster)
			{
				FormApi::makeListForm(function (Player $player, ?int $key) use($gameMaster)
				{
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
										$player->teleport(new Position(254, 107, 254, Server::getInstance()->getLevelByName("ffapvp")));
										$player->setSpawn(new Position(254, 107, 254, Server::getInstance()->getLevelByName("ffapvp")));
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
		);
		return $gameMaster;
	}

	public function createMazaiMaster(): VectorNPC
	{
		if (($level = Server::getInstance()->getLevelByName("lobby")) === null )
		{
			throw new LevelNotFoundException("Fould not found lobby");
		}
		$nbt = Entity::createBaseNBT(new Vector3(287, 10, 270));
		/** @var $mazaiMaster VectorNPC */
		$mazaiMaster = Entity::createEntity( "Human", $level, $nbt );
		$item = Item::get(Item::POTION, 11, 1);
		$mazaiMaster->getInventory()->setItemInHand($item);
		$skin = new Skin("Standard_Custom", base64_decode(file_get_contents("plugins/Games_Core/resources/skins/MazaiNPC")));
		$mazaiMaster->setSkin($skin);
		$mazaiMaster->setCallable
		(
			function(Player $player) use($mazaiMaster)
			{
				FormApi::makeListForm(function (Player $player, ?int $key) use($mazaiMaster) {
					if (!FormApi::formCancelled($key)) {
						switch ($key) {
							case 0:
								if ($mazaiMaster->getMazai()->reduceMazai($player->getName(), 1)) {
									$player->sendMessage(MessagesEnum::MAZAI_SUCCESS);
									$mazaiMaster->getVLevel()->addExp($player->getName(), 300);
									Main::$instance->getScheduler()->scheduleDelayedTask(new LevelCheckingTask(Main::$instance, $player), 20);
								} else {
									$player->sendMessage(MessagesEnum::MAZAI_ERROR);
								}
								break;
							case 1:
								if ($mazaiMaster->getMazai()->reduceMazai($player->getName(), 1)) {
									$player->sendMessage(MessagesEnum::MAZAI_SUCCESS);
									$mazaiMaster->getMoney()->addMoney($player->getName(), 10000);
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
		);
		return $mazaiMaster;
	}

	public function createMazai(): VectorNPC
	{
		if (($level = Server::getInstance()->getLevelByName("lobby")) === null )
		{
			throw new LevelNotFoundException("Fould not found lobby");
		}
		$nbt = Entity::createBaseNBT(new Vector3(260, 4, 265));
		/** @var $mazai VectorNPC */
		$mazai = Entity::createEntity( "Human", $level, $nbt );
		$item = Item::get(Item::POTION, 11, 1);
		$mazai->getInventory()->setItemInHand($item);
		$skin = new Skin("Standard_Custom", base64_decode(file_get_contents("plugins/Games_Core/resources/skins/MazaiNPC")));
		$mazai->setSkin($skin);
		$mazai->setCallable
		(
			function(Player $player) use($mazai)
			{

				FormApi::makeListForm(function (Player $player, ?int $key) use($mazai){
					if (!FormApi::formCancelled($key)) {
						switch ($key) {
							case 0:
								if ($mazai->getMoney()->reduceMoney($player->getName(), 10000)) {
									$player->sendMessage(MessagesEnum::BUY_SUCCESS);
									$mazai->getMazai()->addMazai($player->getName(), 1);
								} else {
									$player->sendMessage(MessagesEnum::BUY_ERROR);
								}
								break;
						}
					}
				})->setTitle("§a魔剤さんの§e変換所")
					->setContent("§6V§bN§eCoin§rを§aMAZAI§rにします。")
					->addButton(new Button("§e1§aMAZAI\n§e10000§6V§bN§eCoin"))
					->sendToPlayer($player);
			}
		);
		return $mazai;
	}
}