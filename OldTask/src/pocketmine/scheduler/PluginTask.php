<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/09
 * Time: 18:13
 */
declare(strict_types = 1);

namespace pocketmine\scheduler;

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
