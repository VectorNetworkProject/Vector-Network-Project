<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/03
 * Time: 20:55
 */

namespace Core\Game\Duel;


use Core\Player\Level;
use Core\Player\Money;
use pocketmine\Player;

class DuelCore
{
	protected $money;
	protected $level;
	protected $players = [];
	protected $playerstatus = [];

	public function __construct()
	{
		$this->money = new Money();
		$this->level = new Level();
	}

	public function EndGame(Player $player, int $type)
	{
		switch ($type) {
			case 1:
				break;
			case 2:
				break;
		}
	}
}