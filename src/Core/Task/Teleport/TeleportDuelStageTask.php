<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/22
 * Time: 13:10
 */

namespace Core\Task\Teleport;


use Core\Task\PluginTask;
use Core\Game\Duel\DuelCore;
use pocketmine\plugin\Plugin;

class TeleportDuelStageTask extends PluginTask
{
    protected $game;
    protected $gameId;
    public function __construct(Plugin $plugin, DuelCore $game, int $gameId)
    {
        parent::__construct($plugin);
        $this->game = $game;
        $this->gameId = $gameId;
    }
    public function onRun(int $currentTick)
    {
        $this->game->startGame($this->gameId, $currentTick);
    }
}