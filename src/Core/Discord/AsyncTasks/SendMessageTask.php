<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/26
 * Time: 13:20
 */

namespace Core\Discord\AsyncTasks;


use Core\Discord\Discord;
use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;

class SendMessageTask extends AsyncTask
{
	protected static $message, $username, $avatarurl;

	/**
	 * SendMessageTask constructor.
	 * @param string $message
	 * @param string $username
	 * @param string $avatarurl
	 */
	public function __construct(string $message, string $username, string $avatarurl = Discord::AVATAR_URL)
	{
		self::$message = $message;
		self::$username = $username;
		self::$avatarurl = $avatarurl;
	}

	public function onRun()
	{
		Internet::postURL(Discord::getWebhook(), ["content" => self::$message, "username" => self::$username, "avatar_url" => self::$avatarurl]);
	}
}