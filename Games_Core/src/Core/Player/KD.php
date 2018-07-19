<?php
/**
 * Created by PhpStorm.
 * User: PCink
 * Date: 2018/07/19
 * Time: 14:27
 */

namespace Core\Player;


use Core\DataFile;

class KD
{
    public function FFAKD(string $name) {
        $datafile = new DataFile($name);
        $data = $datafile->get('FFAPVP');
        $kill = $data['kill'];
        $death = $data['death'];
        if (empty($kill)) {
            return 0;
        } elseif (empty($death)) {
            return 0;
        } else {
            $int = $kill / $death;
            $kd = floor($int * pow(10, 2)) / pow(10, 2);
            return $kd;
        }
    }
}