<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/14
 * Time: 9:15
 */

namespace Core;

class DataFile
{
	private static $folderName = "/players";
	private static $dir;

	public function __construct(string $name)
	{
		self::$dir = Main::$datafolder . self::$folderName . "/" . strtoupper(substr($name, 0, 1)) . "/" . strtolower($name) . "/";
		if (!file_exists(self::$dir)) {
			mkdir(self::$dir, 0755, true);
		}
	}

	/**
	 * @param string $file
	 * @param string $data
	 * @param int $format
	 */
	public function write(string $file, string $data = "", int $format = 0): void
	{
		file_put_contents(self::$dir . $file, base64_encode(gzencode(json_encode($data, $format), 9)));
	}

	/**
	 * @param $file
	 * @param bool $bool
	 * @return string|null
	 */
	public function get($file, $bool = true): ?string
	{
		return file_exists(self::$dir . $file) ? json_decode(gzdecode(base64_decode(file_get_contents(self::$dir . $file))), $bool) : null;
	}

	/**
	 * @param string $dir
	 * @param string $file
	 * @param string $data
	 * @param int $format
	 */
	public static function writeTo(string $dir, string $file, string $data = "", int $format = 0): void
	{
		if (!file_exists($dir)) {
			mkdir($dir, 0755, true);
		}
		file_put_contents($dir . $file, base64_encode(gzencode(json_encode($data, $format), 9)));
	}

	/**
	 * @param string $dir
	 * @param string $file
	 * @param bool $bool
	 * @return string|null
	 */
	public static function readFrom(string $dir, string $file, bool $bool = true): ?string
	{
		return file_exists($dir . $file) ? json_decode(gzdecode(base64_decode(file_get_contents($dir . $file))), $bool) : null;
	}

	/**
	 * @param string $path
	 * @param string $data
	 * @param int $format
	 */
	public static function writeToPath(string $path, string $data = "", int $format = 0): void
	{
		file_put_contents($path, base64_encode(gzencode(json_encode($data, $format), 9)));
	}

	/**
	 * @param string $path
	 * @param bool $bool
	 * @return string|null
	 */
	public static function readFromPath(string $path, bool $bool = true): ?string
	{
		return file_exists($path) ? json_decode(gzdecode(base64_decode(file_get_contents($path))), $bool) : null;
	}
}
