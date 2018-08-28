<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/08/26
 * Time: 12:39
 */

namespace Core\Discord;

use Core\Discord\Threads\SendEmbed;
use Core\Discord\Threads\SendMessage;

class Discord
{
	const AVATAR_URL = "https://cdn.discordapp.com/attachments/462900225716125696/482013778653872169/unknown.png";

	/**
	 * @param string $message
	 */
	public static function SendMessage(string $message)
	{
		$send = new SendMessage(self::getWebhook(), $message);
	}

	public static function SendEmbed(string $title, string $field, string $value, int $color = 16777215)
	{
		$send = new SendEmbed(self::getWebhook(), $title, $field, $value, $color);
		$send->start();
	}

	/**
	 * @return null|string
	 */
	public static function getWebhook(): ?string
	{
		return file_get_contents("plugins/Games_Core/resources/WEBHOOK_URL");
	}
}