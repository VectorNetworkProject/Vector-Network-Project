<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 9:20
 */

namespace Core\Player;


use Core\DataFile;

class Money
{
    /**
     * @param string $name
     * @return int
     */
    public function getMoney(string $name) : int {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        return $data['money'];
    }

    /**
     * @param string $name
     * @param int $money
     */
    public function setMoney(string $name, int $money) {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        $data['money'] = $money;
        $datafile->write('USERDATA', $data);
    }

    /**
     * @param string $name
     * @param int $money
     * @return bool
     */
    public function reduceMoney(string $name, int $money) : bool {
        $datafile = new DataFile($name);
        $data = $datafile->get('USERDATA');
        if ($data['money'] < $money) {
            return false;
        } else {
            $data['money'] = $data['money'] - $money;
            $datafile->write('USERDATA', $data);
            return true;
        }
    }
}