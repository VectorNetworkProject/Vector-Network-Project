<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/11
 * Time: 18:36
 */

namespace Core;

use Core\{
    Checker\PlayerAddressChecker, Commands\adchat, Commands\gamehelp
};

use pocketmine\{
    event\Listener,
    event\player\PlayerJoinEvent,
    event\player\PlayerPreLoginEvent,
    event\player\PlayerQuitEvent,
    plugin\PluginBase,
    utils\TextFormat
};

class Main extends PluginBase implements Listener
{
    public static $instance = null;
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::GREEN."Games_Coreを読み込みました。");
    }
    public function onDisable()
    {
        $this->getLogger()->info(TextFormat::RED."Games_Coreを終了しました。");
    }
    public function onPreLogin(PlayerPreLoginEvent $event)
    {
        $player = $event->getPlayer();
        $check = new PlayerAddressChecker();
        if ($check->Checker($player->getAddress())) {
            $this->getLogger()->info($player->getName()."は国内からのアクセスです。");
        } else {
            $player->kick("§l§6Vector §bNetwork\n§r§fあなたはサーバーからキックされました。\n§7理由: §f国外からのアクセス", false);
            $this->getLogger()->info($player->getName()."は国外からのアクセスの為キックしました。");
        }
    }
    public function onJoin(PlayerJoinEvent $event)
    {
        $event->setJoinMessage(null);
    }
    public function onQuit(PlayerQuitEvent $event)
    {
        $event->setQuitMessage(null);
    }

    private function registerCommands()
    {
        $commands = [
            new gamehelp($this),
            new adchat($this)
       ];
        $this->getServer()->getCommandMap()->registerAll($this->getName(), $commands);
    }
    public function onLoad()
    {
        self::$instance = $this;
        $this->registerCommands();
    }
}
