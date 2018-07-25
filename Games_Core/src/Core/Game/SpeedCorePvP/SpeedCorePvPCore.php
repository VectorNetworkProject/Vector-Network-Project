<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/24
 * Time: 22:36
 */

namespace Core\Game\SpeedCorePvP;


use Core\Main;
use pocketmine\Player;
use pocketmine\utils\Color;

class SpeedCorePvPCore
{
	protected $plugin;
	protected $bluecolor;
	protected $redcolor;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
		$this->bluecolor = new Color(0, 0, 255);
		$this->redcolor = new Color(255, 0, 0);
	}

	public function GameJoin(Player $player)
	{

	}
}