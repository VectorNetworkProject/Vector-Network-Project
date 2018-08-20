<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/29
 * Time: 8:02
 */

namespace Core\Task;


use Core\Main;
use pocketmine\block\Block;
use pocketmine\level\Position;

class AutosetBlockTask extends PluginTask
{
	protected $block;
	public function __construct(Main $plugin, Block $block)
	{
		parent::__construct($plugin);
		$this->block = $block;
	}
	public function onRun(int $currentTick)
	{
		$this->block->getLevel()->setBlock(new Position($this->block->getX(), $this->block->getY(), $this->block->getZ(), $this->block->getLevel()), $this->block);
	}
}