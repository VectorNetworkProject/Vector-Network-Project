<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/11
 * Time: 18:36
 */

namespace Core;

use Core\{
    Commands\gamehelp
};

use pocketmine\{
    plugin\PluginBase,
    utils\TextFormat
};

class Main extends PluginBase
{
    public static $instance = null;
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getLogger()->info(TextFormat::GREEN."Games_Coreを読み込みました。");
    }
    public function onDisable()
    {
        $this->getLogger()->info(TextFormat::RED."Games_Coreを終了しました。");
    }
    private function registerCommands()
    {
        $commands = [
            new gamehelp($this)
       ];
        $this->getServer()->getCommandMap()->registerAll($this->getName(), $commands);
    }
    public function onLoad()
    {
        self::$instance = $this;
        $this->registerCommands();
    }
}
