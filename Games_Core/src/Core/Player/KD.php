<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/19
 * Time: 14:27
 */

namespace Core\Player;

use Core\DataFile;

class KD
{
	/**
	 * @param string $name
	 * @return float|int
	 */
	public function FFAKD(string $name)
	{
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

	/**
	 * @param string $name
	 * @return float|int
	 */
	public function SCPKD(string $name)
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('COREPVP');
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

	/**
	 * @param string $name
	 * @return float|int
	 */
	public function SCPWL(string $name)
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('COREPVP');
		$win = $data['win'];
		$lose = $data['lose'];
		if (empty($win)) {
			return 0;
		} elseif (empty($lose)) {
			return 0;
		} else {
			$int = $win / $lose;
			$kd = floor($int * pow(10, 2)) / pow(10, 2);
			return $kd;
		}
	}
}
