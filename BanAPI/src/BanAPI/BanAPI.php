<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/06
 * Time: 22:41
 */

namespace BanAPI;


use pocketmine\plugin\PluginBase;

class BanAPI extends PluginBase
{
    public function onEnable() : void
    {
        $this->getLogger()->info("BanAPIを読み込みました。");
    }
}