<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/28
 * Time: 22:54
 */

namespace Core\Discord\Threads;


use Core\Discord\Discord;
use pocketmine\Thread;
use pocketmine\utils\InternetException;

class SendMessage extends Thread
{
	protected static $webhook, $message, $username, $avatarurl;

	/**
	 * SendMessage constructor.
	 * @param string $webhook
	 * @param string $message
	 * @param string $username
	 * @param string $avatarurl
	 */
	public function __construct(string $webhook, string $message, string $username = "Vector Network", string $avatarurl = Discord::AVATAR_URL)
	{
		self::$webhook = $webhook;
		self::$message = $message;
		self::$username = $username;
		self::$avatarurl = $avatarurl;
	}

	public function run()
	{
		/**
		 * @param string $webhook
		 * @param $data
		 * @param callable|null $onSuccess
		 * @return array
		 */
		function post(string $webhook, $data, callable $onSuccess = null)
		{
			$ch = curl_init($webhook);
			curl_setopt_array($ch, [] + [
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => $data,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => 2,
					CURLOPT_FORBID_REUSE => 1,
					CURLOPT_FRESH_CONNECT => 1,
					CURLOPT_AUTOREFERER => true,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_CONNECTTIMEOUT_MS => (int)(10 * 1000),
					CURLOPT_TIMEOUT_MS => (int)(10 * 1000),
					CURLOPT_HTTPHEADER => array_merge(["User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0 " . \pocketmine\NAME], array()),
					CURLOPT_HEADER => true
				]);
			try {
				$raw = curl_exec($ch);
				$error = curl_error($ch);
				if ($error !== "") {
					throw new InternetException($error);
				}
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
				$rawHeaders = substr($raw, 0, $headerSize);
				$body = substr($raw, $headerSize);
				$headers = [];
				foreach (explode("\r\n\r\n", $rawHeaders) as $rawHeaderGroup) {
					$headerGroup = [];
					foreach (explode("\r\n", $rawHeaderGroup) as $line) {
						$nameValue = explode(":", $line, 2);
						if (isset($nameValue[1])) {
							$headerGroup[trim(strtolower($nameValue[0]))] = trim($nameValue[1]);
						}
					}
					$headers[] = $headerGroup;
				}
				if ($onSuccess !== null) {
					$onSuccess($ch);
				}
				return [$body, $headers, $httpCode];
			} finally {
				curl_close($ch);
			}
		}

		post(self::$webhook, json_encode([
			"username" => self::$username,
			"avatar_url" => self::$avatarurl,
			"content" => self::$message
		]));
	}
}