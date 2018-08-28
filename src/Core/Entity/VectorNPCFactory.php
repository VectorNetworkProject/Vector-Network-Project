<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/28
 * Time: 16:47
 */

namespace Core\Entity;


use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Server;

class VectorNPCFactory
{
	public function createGameMaster(): GameMaster
	{
		if (($level = Server::getInstance()->getLevelByName("lobby")) === null )
		{
			throw new LevelNotFoundException("Fould not found lobby");
		}
		$nbt = Entity::createBaseNBT(new Vector3(260, 4, 265));
		/** @var $gameMaster GameMaster */
		$gameMaster = Entity::createEntity( Human, $level, $nbt );
		$item = Item::get(Item::POTION, 11, 1);
		$gameMaster->getInventory()->setItemInHand($item);
		$skin = new Skin("Standard_Custom", base64_decode(file_get_contents("plugins/Games_Core/resources/skins/GameMaster")));
		$gameMaster->setSkin($skin);
		return $gameMaster;
	}

	public function createMazaiMaster(): MazaiMaster
	{

	}

	public function createMazai(): Mazai
	{

	}
}