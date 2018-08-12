<?php
/*
 * __      __       _             _   _      _                      _    _____           _           _
 * \ \    / /      | |           | \ | |    | |                    | |  |  __ \         (_)         | |
 *  \ \  / /__  ___| |_ ___  _ __|  \| | ___| |___      _____  _ __| | _| |__) | __ ___  _  ___  ___| |_
 *   \ \/ / _ \/ __| __/ _ \| '__| . ` |/ _ \ __\ \ /\ / / _ \| '__| |/ /  ___/ '__/ _ \| |/ _ \/ __| __|
 *    \  /  __/ (__| || (_) | |  | |\  |  __/ |_ \ V  V / (_) | |  |   <| |   | | | (_) | |  __/ (__| |_
 *     \/ \___|\___|\__\___/|_|  |_| \_|\___|\__| \_/\_/ \___/|_|  |_|\_\_|   |_|  \___/| |\___|\___|\__|
 *                                                                                     _/ |
 *                                                                                    |__/
 * Developers: InkoHX & MazaiCrafty
 * ServerSoftware: PocketMine-MP
 * LICENSE: MIT
 */

namespace Core;

use Core\Commands\adtag;
use Core\Commands\debug;
use Core\Commands\gamestatus;
use Core\Commands\killsound;
use Core\Commands\ping;
use Core\Commands\rankshop;
use Core\Commands\selectgame;
use Core\Commands\setmoney;
use Core\Commands\settag;
use Core\Commands\stats;
use Core\Task\AutoSavingTask;
// TODO use Core\Task\FoodTask;
use Core\Task\RemoveItemTask;
use Core\Task\Tip;
use Core\Player\Tag;

use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase
{
	private const START_MESSAGE = "\n
§6__     __        _            §b _   _      _                      _   §e ____            _           _   
§6\ \   / /__  ___| |_ ___  _ __§b| \ | | ___| |___      _____  _ __| | _§e|  _ \ _ __ ___ (_) ___  ___| |_ 
§6 \ \ / / _ \/ __| __/ _ \| '__§b|  \| |/ _ \ __\ \ /\ / / _ \| '__| |/ /§e |_) | '__/ _ \| |/ _ \/ __| __|
§6  \ V /  __/ (__| || (_) | |  §b| |\  |  __/ |_ \ V  V / (_) | |  |   <§e|  __/| | | (_) | |  __/ (__| |_ 
§6   \_/ \___|\___|\__\___/|_|  §b|_| \_|\___|\__| \_/\_/ \___/|_|  |_|\_\§e_|   |_|  \___// |\___|\___|\__|
                                                                                   |__/               
                     §7Developers: §bInkoHX & MazaiCrafty
                     §aLICENSE: §cMIT
                     §c動作環境: §bPocketMine-MP §e4.0.0+dev.1364
    ";

	/** @var Main */
	public static $instance;
	/** @var string */
	public static $datafolder;
	/** @var string[] */
	public static $levels = [
		"ffapvp",
		"corepvp",
		"athletic",
		"Survival"
	];

	public function onLoad(): void
	{
		self::$instance = $this;
		$this
			->loadLevels()
			->registerCommands();
	}

	public function onEnable(): void
	{
		date_default_timezone_set("Asia/Tokyo");
		self::$datafolder = $this->getDataFolder();
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getScheduler()->scheduleRepeatingTask(new Tip($this), 180 * 20);
		$this->getScheduler()->scheduleRepeatingTask(new AutoSavingTask($this), 10 * 20);
		$this->getScheduler()->scheduleRepeatingTask(new RemoveItemTask($this), 30 * 20);
		@mkdir(Main::getDataFolder(), 0755);
		$this->saveDefaultConfig();
		$this->getLogger()->info(self::START_MESSAGE);
		Tag::registerColors();
	}

	public function onDisable(): void
	{
		$this->getLogger()->info(TextFormat::RED . "Games_Coreを終了しました。");
	}

	private function loadLevels(): self
	{
		$server = $this->getServer();
		foreach (self::$levels as $levelName) {
			$server->loadLevel($levelName);
			$level = $server->getLevelByName($levelName);
			if ($level === null) {
				$this->getLogger()->error("ワールド: " . $levelName . " が存在しません。");
			}else {
				$level->setTime(Level::TIME_FULL);
				$level->stopTime();
				$this->getLogger()->info("Level: " . $levelName . " を読み込みました");
			}
		}
		return $this;
	}

	private function registerCommands(): self
	{
		$commands = [
			new ping($this),
			new stats($this),
			new rankshop($this),
			new setmoney($this),
			new settag($this),
			new selectgame($this),
			new debug($this),
			new killsound($this),
			new adtag($this),
			new gamestatus($this)
		];
		$this->getServer()->getCommandMap()->registerAll($this->getName(), $commands);
		return $this;
	}
}
