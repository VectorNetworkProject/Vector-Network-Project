<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/06/11
 * Time: 18:51
 */

namespace Core\Player;

use pocketmine\{
    utils\Utils
};

class PlayerAddressChecker
{
    /**
     * @param string $ip
     * @return bool
     */
    public function Checker(string $ip) : bool
    {
        $userip = explode('.', $ip);
        if ($ip === "127.0.0.1" || ($userip[0] === "192" && $userip[1] === "168")) {
            return true;
        } else {
            $data = json_decode(Utils::getURL("http://freegeoip.net/json/" . $ip), true);
            switch ($data["country_code"]) {
                case "JP":
                    return true;
                case "":
                    return false;
                default:
                    return false;
            }
        }
    }
}
