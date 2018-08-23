<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/21
 * Time: 10:43
 */

namespace Core\Commands;

use Core\Main;
use Core\Task\Teleport\TeleportAthleticTask;
use Core\Task\Teleport\TeleportFFAPvPTask;
use Core\Task\Teleport\TeleportLobbyTask;
use Core\Task\Teleport\TeleportSpeedCorePvPTask;
use Core\Task\Teleport\TeleportSurvivalTask;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use tokyo\pmmp\libform\element\Button;
use tokyo\pmmp\libform\FormApi;

class selectgame extends PluginCommand
{
    protected $plugin;
    public function __construct(Main $plugin)
    {
        parent::__construct("selectgame", $plugin);
        $this->setPermission("vector.network.player");
        $this->setDescription("遊びたいゲームを選択できます。");
        $this->plugin = $plugin;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$this->plugin->isEnabled()) {
            return false;
        }
        if (!$this->testPermission($sender)) {
            return false;
        }
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED."このコマンドはプレイヤーのみが実行できます。");
            return true;
        }
        FormApi::makeListForm(function(Player $player, ?int $key) {
			if (!FormApi::formCancelled($key)) {
				$level = $player->getLevel();
				switch ($key) {
					case 0:
						if ($level->getName() === "lobby") {
							$player->sendMessage("§c既にロビーに居ます。");
						} else {
							$player->sendMessage("§e10秒後テレポートします。");
							$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportLobbyTask($this->plugin, $player), 10 * 20);
						}
						break;
					case 1:
						if ($level->getName() === "ffapvp") {
							$player->sendMessage("§c既にFFAPvPに居ます");
						} else {
							if ($level->getName() === "lobby") {
								$player->teleport(new Position(254, 107, 254, $this->plugin->getServer()->getLevelByName("ffapvp")));
								$player->setSpawn(new Position(254, 107, 254, $this->plugin->getServer()->getLevelByName("ffapvp")));
								$player->sendMessage("§aテレポートしました。");
							} else {
								$player->sendMessage("§e10秒後テレポートします。");
								$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportFFAPvPTask($this->plugin, $player), 10 * 20);
							}
						}
						break;
					case 2:
						if ($level->getName() === "corepvp") {
							$player->sendMessage("§c既にCorePvPに居ます");
						} else {
							if ($level->getName() === "lobby") {
								$player->teleport(new Position(255, 8, 257, $this->plugin->getServer()->getLevelByName("corepvp")));
								$player->sendMessage("§aテレポートしました。");
							} else {
								$player->sendMessage("§e10秒後テレポートします。");
								$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportSpeedCorePvPTask($this->plugin, $player), 10 * 20);
							}
						}
						break;
					case 3:
						if ($player->isOp()) {
							if ($level->getName() === "athletic") {
								$player->sendMessage("§c既にAthleticに居ます");
							} else {
								if ($level->getName() === "lobby") {
									$player->teleport(new Position(254, 4, 254, $this->plugin->getServer()->getLevelByName("athletic")));
									$player->sendMessage("§aテレポートしました。");
								} else {
									$player->sendMessage("§e10秒後テレポートします。");
									$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportAthleticTask($this->plugin, $player), 10 * 20);
								}
							}
						} else {
							$player->sendMessage("現在開発者のみがテレポートする事が出来ます。");
						}
						break;
					case 4:
						FormApi::makeModalForm(function (Player $player, ?bool $bool) {
							if (!FormApi::formCancelled($bool)) {
								if ($bool) {
									$level = $player->getLevel();
									if ($level->getName() === "Survival") {
										$player->sendMessage("§c既にSurvivalに居ます");
									} else {
										if ($level->getName() === "lobby") {
											$player->teleport(new Position(225, 243, 256, $this->plugin->getServer()->getLevelByName("Survival")));
											$player->setSpawn(new Position(225, 243, 256, $this->plugin->getServer()->getLevelByName("Survival")));
											$player->sendMessage("§aテレポートしました。");
										} else {
											$player->sendMessage("§e10秒後テレポートします。");
											$this->plugin->getScheduler()->scheduleDelayedTask(new TeleportSurvivalTask($this->plugin, $player), 10 * 20);
										}
									}
								}
							}
						})->setTitle("注意")
							->setContent("このゲームのステージはかなり重くアプリがクラッシュする事があります。\nそれでも参加したい方は諦めずに参加を繰り返して下さい。")
							->setButtonText(true, "俺の端末にクラッシュなんてねぇ")
							->setButtonText(false, "いや俺の端末はクソだから...")
							->sendToPlayer($player);
						break;
				}
			}
		})->setTitle("ゲーム選択")
			->setContent("遊びたいゲームを選択してください")
			->addButton(new Button("§eロビー"))
			->addButton(new Button("§6FFA§cPvP"))
			->addButton(new Button("§bSpeed§aCore§cPvP"))
			->addButton(new Button("§dAthletic"))
			->addButton(new Button("§aSurvival"))
			->sendToPlayer($sender);
        return true;
    }
}
