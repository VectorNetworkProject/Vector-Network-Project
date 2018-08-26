<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/26
 * Time: 13:38
 */

namespace Core\Discord\AsyncTasks;


use Core\Discord\Discord;
use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;

class SendEmbedTask extends AsyncTask
{
	protected static $status, $username, $avatarurl, $message;

	/**
	 * SendEmbedTask constructor.
	 * @param string $status
	 * @param string $message
	 * @param string $username
	 * @param string $avatarurl
	 */
	public function __construct(string $status, string $message, string $username, string $avatarurl = Discord::AVATAR_URL)
	{
		self::$status = $status;
		self::$message = $message;
		self::$username = $username;
		self::$avatarurl = $avatarurl;
	}

	public function onRun()
	{
		Internet::postURL(Discord::getWebhook(), ["embeds" => [["title" => "ステータスメッセージ", "type" => "rich","fields" => [["name" => self::$status,"value" => self::$message,"inline" => false]],"footer" => ["text" => "Developed by VectorNetworkProject","icon_url" => "https://avatars2.githubusercontent.com/u/41660146?s=200&v=4"]]]]);
	}
}