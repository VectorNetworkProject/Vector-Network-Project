<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/22
 * Time: 18:08
 */

namespace Core\Task;


use Core\Main;
use Core\Player\Level;
use Core\Player\Money;
use Core\Player\Rank;
use Core\Player\Tag;
use pocketmine\Player;

class StatusTask extends PluginTask
{
    protected $player;
    protected $money;
    protected $level;
    protected $rank;
    protected $tag;
    public function __construct(Main $plugin, Player $player)
    {
        parent::__construct($plugin);
        $this->player = $player;
        $this->money = new Money();
        $this->level = new Level($plugin);
        $this->rank = new Rank($plugin);
        $this->tag = new Tag($plugin);
    }
    public function onRun(int $currentTick)
    {
        $player = $this->player->getPlayer();
        $money = $this->money->getMoney($player->getName());
        $level = $this->level->getLevel($player->getName());
        $exp = $this->level->getExp($player->getName());
        $maxexp = $this->level->getMaxExp($player->getName());
        $playerrank = $this->rank->getRank($player->getName());
        $tag = $this->tag->getTag($player);
        $player->sendPopup("\n\n\n\n\n                                 §a---===< §eSTATUS §a>===---\n                                 §bLevel: $level\n                                 §aEXP: $exp / $maxexp\n                                 §6V§bN§eCoin§r: §6$money §eCoin\n                                 §dRank: §r$playerrank\n                                 §2Tag: §r$tag");
    }
}