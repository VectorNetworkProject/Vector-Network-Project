<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/27
 * Time: 23:54
 */

namespace Core\Game;


use pocketmine\Player;

interface Game
{
	public function start();
	public function finish();
	public function isPlaying();
	public function join(Player $player);
	public function quit(Player $player);
	public function powerQuit(Player $player);
}