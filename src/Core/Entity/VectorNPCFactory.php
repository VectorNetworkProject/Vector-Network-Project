<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/28
 * Time: 16:47
 */

namespace Core\Entity;


use Core\Commands\MessagesEnum;
use Core\Main;
use Core\Task\LevelCheckingTask;
use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\FormApi;

class VectorNPCFactory
{
	public static function createGameMaster(): VectorNPC
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
		return $gameMaster;
	}

	public static function createMazaiMaster(): VectorNPC
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
									$mazaiMaster->level->addExp($player->getName(), 300);
									Main::$instance->getScheduler()->scheduleDelayedTask(new LevelCheckingTask(Main::$instance, $player), 20);
								} else {
									$player->sendMessage(MessagesEnum::MAZAI_ERROR);
								}
								break;
							case 1:
								if ($mazaiMaster->getMazai()->reduceMazai($player->getName(), 1)) {
									$player->sendMessage(MessagesEnum::MAZAI_SUCCESS);
									$mazaiMaster->getMazai()->addMoney($player->getName(), 10000);
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

	public static function createMazai(): VectorNPC
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
		return $mazai;
	}
}