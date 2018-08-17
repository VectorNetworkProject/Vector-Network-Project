<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/05
 * Time: 14:06
 */

namespace Core\Player;


use Core\DataFile;

class MazaiPoint
{
	/**
	 * @param string $name
	 * @return int
	 */
	public function getMazai(string $name): int
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('USERDATA');
		return $data['mazaipoint'];
	}

	/**
	 * @param string $name
	 * @param int $point
	 */
	public function addMazai(string $name, int $point)
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('USERDATA');
		$data['mazaipoint'] += $point;
		$datafile->write('USERDATA', $data);
	}

	/**
	 * @param string $name
	 * @param int $point
	 * @return bool
	 */
	public function reduceMazai(string $name, int $point): bool
	{
		$datafile = new DataFile($name);
		$data = $datafile->get('USERDATA');
		if ($data['mazaipoint'] < $point) {
			return false;
		} else {
			$data['mazaipoint'] = $data['mazaipoint'] - $point;
			$datafile->write('USERDATA', $data);
			return true;
		}
	}
}