<?php
/**
 * Created by PhpStorm.
 * User: PCink
 * Date: 2018/07/18
 * Time: 10:37
 */

namespace Core\Task;


use pocketmine\Player;
use pocketmine\plugin\Plugin;

class JoinTitle extends PluginTask
{
    protected $player;
    public function __construct(Plugin $plugin, Player $player)
    {
        parent::__construct($plugin);
        $this->player = $player;
    }

    /**
     * Actions to execute when run
     *
     * @param int $currentTick
     *
     * @return void
     */
    public function onRun(int $currentTick)
    {
        $player = $this->player;
        $player->addTitle("§6Vector §bNetwork", "§eDeveloped by InkoHX", 5, 5, 5);
        $player->sendMessage("§a---===< §6Vector §bNetwork §eProject §a>===---\n§bDeveloped by InkoHX\n§bGitHub: §7https://github.com/InkoHX/Vector-Network-Project\n§bTwitter: §7https://twitter.com/InkoHX\n§9Discord: §7https://discord.gg/EF2G5dh\n§a---=============================---");
    }
}