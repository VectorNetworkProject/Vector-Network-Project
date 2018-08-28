<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/26
 * Time: 12:39
 */

namespace Core\Discord;


use Core\Discord\AsyncTasks\SendEmbedTask;
use Core\Discord\AsyncTasks\SendMessageTask;
use Core\Main;

class Discord
{
	const AVATAR_URL = "https://cdn.discordapp.com/attachments/462900225716125696/482013778653872169/unknown.png";

	/**
	 * @param string $message
	 * @param string $usename
	 */
	public static function SendMessage(string $message, string $usename = "Vector Network")
	{
		Main::$instance->getServer()->getAsyncPool()->submitTask(new SendMessageTask($message, $usename));
	}

	/**
	 * @param string $status
	 * @param string $message
	 * @param int $color
	 * @param string $username
	 */
	public static function SendEmbed(string $status, string $message, int $color, string $username = "Vector Network")
	{
		Main::$instance->getServer()->getAsyncPool()->submitTask(new SendEmbedTask($status, $message, $username, $color));
	}

	/**
	 * @return null|string
	 */
	public static function getWebhook(): ?string
	{
		return file_get_contents("plugins/Games_Core/resources/WEBHOOK_URL");
	}
}