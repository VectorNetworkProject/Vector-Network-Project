<?php
/**
 * Created by PhpStorm.
 * User: souta
 * Date: 2018/06/16
 * Time: 21:52
 */

namespace Core\Commands;


use Core\{
    Checker\AdminPlayer, Main
};

use pocketmine\{
    command\CommandSender, command\PluginCommand, Player
};

class adchat extends PluginCommand
{
    private $plugin;
    public function __construct(Main $plugin)
    {
        parent::__construct("adchat", $plugin);
        $this->setDescription("オペレーター権限を持っている人が使えるコマンド(Admin Only)");
        $this->setPermission("vector.network.admin");
        $this->plugin = $plugin;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($this->testPermission($sender)) return false;
        if ($this->plugin->isEnabled()) return false;
        if (!isset($args[0])) return false;
        if ($sender instanceof Player){
            $admin = new AdminPlayer();
            $admin->AdminMessage($sender->getName(), $args[0]);
            return true;
        } else {
            $admin = new AdminPlayer();
            $admin->AdminMessage("CONSOLE", $args[0]);
            return true;
        }
    }
}