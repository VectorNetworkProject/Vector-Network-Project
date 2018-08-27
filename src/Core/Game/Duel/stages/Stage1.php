<?php

namespace Core\Game\Duel\stages;

use pocketmine\math\Vector3;

class Stage1
{

	public function getSpawnPosition()
	{
		return [
			new Vector3(0, 0, 0),
			new Vector3(0, 0, 0),
		];
	}
}