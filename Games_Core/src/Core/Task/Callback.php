<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/17
 * Time: 13:16
 */

namespace Core\Task;

use pocketmine\scheduler\Task;

class Callback extends Task
{

    /** @var callable */
    protected $callable;
    /** @var array */
    protected $args;

    /**
     * Callback constructor.
     * @param callable $callable
     * @param array $args
     */
    public function __construct(callable $callable, array $args = [])
    {
        $this->callable = $callable;
        $this->args = $args;
        $this->args[] = $this;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param int $currentTicks
     */
    public function onRun($currentTicks)
    {
        call_user_func_array($this->callable, $this->args);
    }
}
