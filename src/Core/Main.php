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
use Core\Event\BlockBreak;
use Core\Event\BlockPlace;
use Core\Event\EntityDamage;
use Core\Event\EntityInventoryChange;
use Core\Event\EntityShootBow;
use Core\Event\PlayerCommandPreprocess;
use Core\Event\PlayerDeath;
use Core\Event\PlayerExhaust;
use Core\Event\PlayerJoin;
use Core\Event\PlayerLogin;
use Core\Event\PlayerMove;
use Core\Event\PlayerPreLogin;
use Core\Event\PlayerQuit;
use Core\Event\PlayerRespawn;
use Core\Task\AutoSavingTask;
use Core\Task\MOTDTip;
use Core\Task\RemoveItemTask;
use Core\Task\Tip;
use Core\Player\Tag;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\libform\FormApi;

class Main extends PluginBase
{
	private const START_MESSAGE = "\n
§6__     __        _            §b _   _      _                      _   §e ____            _           _   
§6\ \   / /__  ___| |_ ___  _ __§b| \ | | ___| |___      _____  _ __| | _§e|  _ \ _ __ ___ (_) ___  ___| |_ 
§6 \ \ / / _ \/ __| __/ _ \| '__§b|  \| |/ _ \ __\ \ /\ / / _ \| '__| |/ /§e |_) | '__/ _ \| |/ _ \/ __| __|
§6  \ V /  __/ (__| || (_) | |  §b| |\  |  __/ |_ \ V  V / (_) | |  |   <§e|  __/| | | (_) | |  __/ (__| |_ 
§6   \_/ \___|\___|\__\___/|_|  §b|_| \_|\___|\__| \_/\_/ \___/|_|  |_|\_\§e_|   |_|  \___// |\___|\___|\__|
                                                                                   |__/               
                     §7Developers: §bInkoHX & MazaiCrafty & yuko fuyutsuki & DusKong
                     §aLICENSE: §cMIT
                     §c動作環境: §bPocketMine-MP §e4.0.0+dev.1405
    ";

	/** @var Main */
	public static $instance;

	/** @var string */
	public static $datafolder;

	/** @var bool */
	public static $isDev = true;

	public function onLoad(): void
	{
		self::$instance = $this;
		$this->registerCommands();
	}

	public function onEnable(): void
	{
		date_default_timezone_set("Asia/Tokyo");
		self::$datafolder = $this->getDataFolder();
		self::loadLevels();
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		// TODO: $this->registerEvents();
		$this->getScheduler()->scheduleRepeatingTask(new Tip($this), 180 * 20);
		$this->getScheduler()->scheduleRepeatingTask(new AutoSavingTask($this), 10 * 20);
		$this->getScheduler()->scheduleRepeatingTask(new RemoveItemTask($this), 30 * 20);
		$this->getScheduler()->scheduleRepeatingTask(new MOTDTip($this), 30 * 20);
		$this->saveDefaultConfig();
		foreach ($this->getServer()->getLevels() as $level) {
			$level->setTime(6000);
			$level->stopTime();
		}
		Tag::registerColors();
		FormApi::register($this);
		$this->getLogger()->info(self::START_MESSAGE);
	}

	public function onDisable(): void
	{
		$this->getLogger()->info(TextFormat::RED . "Games_Coreを終了しました。");
	}

	private function loadLevels(): self
	{
		foreach (scandir('worlds/') as $levelName) {
			if ($levelName === '.' or $levelName === '..' or $levelName === $this->getServer()->getDefaultLevel()->getName()) {
				continue;
			}
			$this->getServer()->loadLevel($levelName);
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

	private function registerEvents(): self
	{
		$plm = $this->getServer()->getPluginManager();
		$plm->registerEvents(new BlockBreak($this), $this);
		$plm->registerEvents(new BlockPlace($this), $this);
		$plm->registerEvents(new EntityDamage($this), $this);
		$plm->registerEvents(new EntityInventoryChange($this), $this);
		$plm->registerEvents(new EntityShootBow($this), $this);
		$plm->registerEvents(new PlayerCommandPreprocess($this), $this);
		$plm->registerEvents(new PlayerDeath($this), $this);
		$plm->registerEvents(new PlayerExhaust($this), $this);
		$plm->registerEvents(new PlayerJoin($this), $this);
		$plm->registerEvents(new PlayerLogin($this), $this);
		$plm->registerEvents(new PlayerMove($this), $this);
		$plm->registerEvents(new PlayerPreLogin($this), $this);
		$plm->registerEvents(new PlayerQuit($this), $this);
		$plm->registerEvents(new PlayerRespawn($this), $this);
		return $this;
	}

	public static function isDev(): bool
	{
		return self::$isDev;
	}
}
