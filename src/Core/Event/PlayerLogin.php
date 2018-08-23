<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 12:25
 */

namespace Core\Event;

use Core\DataFile;
use Core\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class PlayerLogin implements Listener
{
	private $plugin;

	public function __construct(Main $plugin)
	{
		$this->plugin = $plugin;
	}

	public function event(PlayerLoginEvent $event): void
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		$data = new DataFile($name);
		if (($user = $data->get("USERDATA")) === null) {
			$user = [
				"name" => $name,
				"money" => 1000,
				"networklevel" => 1,
				"exp" => 0,
				"maxexp" => 50,
				"rank" => "§rNoRank",
				"tag" => "§rNoTag",
				"mazaipoint" => 0,
				"killsound" => 0,
				"firstlogin" => date("Y年m月d日 H時i分s秒"),
				"lastlogin" => date("Y年m月d日 H時i分s秒")
			];
			$data->write("USERDATA", $user);
			$this->plugin->getServer()->broadcastMessage("§7[§b情報§7] $name は初参加です。");
		}
		if (($ffapvp = $data->get("FFAPVP")) === null) {
			$ffapvp = [
				"name" => $name,
				"kill" => 0,
				"death" => 0
			];
			$data->write('FFAPVP', $ffapvp);
		}
		if (($corepvp = $data->get("COREPVP")) === null) {
			$corepvp = [
				"name" => $name,
				"kill" => 0,
				"death" => 0,
				"breakcore" => 0,
				"win" => 0,
				"lose" => 0
			];
			$data->write('COREPVP', $corepvp);
		}
		if (($inventory = $data->get('SURVIVAL')) === null) {
			$inventory = [
				"breakblock" => 0,
				"placeblock" => 0,
				"kill" => 0,
				"death" => 0,
				"breakdiamond" => 0,
				"breakgold" => 0,
				"breakcoal" => 0,
				"breakiron" => 0,
				"health" => 20,
				"food" => 20,
				"items" => array(),
				"x" => 225,
				"y" => 243,
				"z" => 256
			];
			$data->write('SURVIVAL', $inventory);
		}
		if (($duel = $data->get('DUEL')) === null) {
			$duel = [
				"win" => 0,
				"lose" => 0
			];
			$data->write('DUEL', $duel);
		}
	}
}
