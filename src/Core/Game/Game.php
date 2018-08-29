<?php
/**
 * Created by PhpStorm.
 * User: UramnOIL
 * Date: 2018/08/27
 * Time: 23:54
 */

namespace Core\Game;


use pocketmine\Player;

interface IGame
{
	public function start();
	public function finish();
	public function isPlaying();
	public function onJoin(Player $player);
	public function onQuit(Player $player);
}