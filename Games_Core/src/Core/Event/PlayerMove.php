<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 17:12
 */

namespace Core\Event;

use Core\Main;
use Core\Player\Level;
use Core\Player\Money;
use Core\Player\Rank;
use Core\Player\Tag;
use pocketmine\event\player\PlayerMoveEvent;

class PlayerMove
{
    protected $money, $level, $plugin, $rank, $tag;
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->money = new Money();
        $this->level = new Level();
        $this->rank = new Rank($this->plugin);
        $this->tag = new Tag();
    }

    public function event(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $money = $this->money->getMoney($player->getName());
        $level = $this->level->getLevel($player->getName());
        $exp = $this->level->getExp($player->getName());
        $maxexp = $this->level->getMaxExp($player->getName());
        $playerrank = $this->rank->getRank($player->getName());
        $tag = $this->tag->getTag($player);
        $player->sendPopup("\n\n\n\n\n                                 §a---===< §eSTATUS §a>===---\n                                 §bLevel: $level\n                                 §aEXP: $exp / $maxexp\n                                 §6V§bN§eCoin§r: §6$money §eCoin\n                                 §dRank: §7$playerrank\n                                 §1Tag: $tag");
    }
}