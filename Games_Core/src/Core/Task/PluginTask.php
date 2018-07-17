<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/17
 * Time: 13:20
 */
namespace Core\Task;

use pocketmine\scheduler\Task;
use pocketmine\plugin\Plugin;

abstract class PluginTask extends Task
{
    /** @var Plugin */
    protected $owner;

    /**
     * PluginTask constructor.
     * @param Plugin $owner
     */
    public function __construct(Plugin $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return Plugin
     */
    final public function getOwner() : Plugin
    {
        return $this->owner;
    }
}
