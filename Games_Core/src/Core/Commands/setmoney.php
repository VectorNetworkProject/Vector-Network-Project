<?php
/**
 * Created by PhpStorm.
 * User: PCink
 * Date: 2018/07/19
 * Time: 15:31
 */

namespace Core\Commands;

use Core\Main;
use Core\Player\Money;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class setmoney extends PluginCommand
{
    protected $plugin;
    protected $money;
    public function __construct(Main $plugin)
    {
        parent::__construct("setmoney", $plugin);
        $this->setPermission("vector.network.admin");
        $this->setDescription("Admin Command");
        $this->plugin = $plugin;
        $this->money = new Money();
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$this->plugin->isEnabled()) {
            return false;
        }
        if (!$this->testPermission($sender)) {
            return false;
        }
        if (isset($args[0])) {
            if (isset($args[1])) {
                $this->money->setMoney($args[0], $args[1]);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
