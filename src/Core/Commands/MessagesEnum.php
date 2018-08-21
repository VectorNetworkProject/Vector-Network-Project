<?php
namespace Core\Commands;

/**
 * @deprecated
 * TODO: メッセージ関連は設定ファイルから読み込むようにしたほうが良い
 * Class MessagesEnum
 * @package Core\Commands
 */
class MessagesEnum
{
	const BUY_SUCCESS = "§7[§a成功§7] §a購入に成功しました。";
	const BUY_ERROR = "§7[§c失敗§7] §r§6V§bN§eCoin§cがたりません。";
	const MAZAI_SUCCESS = "§7[§a成功§7] §a購入に成功しました。";
	const MAZAI_ERROR = "§7[§c失敗§7] §r§aMAZAI§cが足りません";
}