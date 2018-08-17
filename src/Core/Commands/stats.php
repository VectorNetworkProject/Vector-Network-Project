<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/07/17
 * Time: 12:50
 */

namespace Core\Commands;

use Core\DataFile;
use Core\Game\Survival\SurvivalCore;
use Core\Main;
use Core\Player\KD;
use Core\Player\Level;
use Core\Player\MazaiPoint;
use Core\Player\Money;
use Core\Player\Rank;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\FormApi;

class stats extends PluginCommand
{
	protected $plugin;
	protected $level;
	protected $money;
	protected $rank;
	protected $kd;
	protected $mazai;

	public function __construct(Main $plugin)
	{
		parent::__construct("stats", $plugin);
		$this->setPermission("vector.network.player");
		$this->setDescription("自分のステータスを表示します。");
		$this->plugin = $plugin;
		$this->level = new Level();
		$this->money = new Money();
		$this->rank = new Rank($this->plugin);
		$this->kd = new KD();
		$this->mazai = new MazaiPoint();
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$this->plugin->isEnabled()) {
			return false;
		}
		if (!$this->testPermission($sender)) {
			return false;
		}
		if ($sender instanceof Player) {
			$name = $sender->getName();
			$datafile = new DataFile($name);
			$userdata = $datafile->get('USERDATA');
			$ffapvp = $datafile->get('FFAPVP');
			$speedcorepvp = $datafile->get('COREPVP');
			$level = $this->level->getLevel($name);
			$money = $this->money->getMoney($name);
			$exp = $this->level->getExp($name);
			$firstlogin = $userdata['firstlogin'];
			$lastlogin = $userdata['lastlogin'];
			$maxexp = $userdata['maxexp'] - $exp;
			$mazai = $this->mazai->getMazai($sender->getName());
			$ffapvp_kill = $ffapvp['kill'];
			$ffapvp_death = $ffapvp['death'];
			$ffapvp_kd = $this->kd->FFAKD($name);
			$rank = $this->rank->getRank($name);
			$tag = $userdata['tag'];
			$speedcorepvp_kill = $speedcorepvp['kill'];
			$speedcorepvp_death = $speedcorepvp['death'];
			$speedcorepvp_breakcore = $speedcorepvp['breakcore'];
			$speedcorepvp_win = $speedcorepvp['win'];
			$speedcorepvp_lose = $speedcorepvp['lose'];
			$speedcorepvp_kd = $this->kd->SCPKD($sender->getName());
			$speedcorepvp_wl = $this->kd->SCPWL($sender->getName());
			$survival_kill = SurvivalCore::getKillCount($name);
			$survival_death = SurvivalCore::getDeathCount($name);
			$survival_place = SurvivalCore::getPlaceCount($name);
			$survival_breakblock = SurvivalCore::getBreakBlock($name);
			$survival_breakdiamond = SurvivalCore::getBreakDiamond($name);
			$survival_breakiron = SurvivalCore::getBreakIron($name);
			$survival_breakgold = SurvivalCore::getBreakGold($name);
			$survival_breakcoal = SurvivalCore::getBreakCoal($name);

			$custom = FormApi::makeCustomForm(function (?array $response) {
				if (FormApi::formCancelled($response)) {
					// フォームをキャンセルしていなければ
				}
			});
			$custom->setTitle("§l$name のステータス")
				->addElement(new Label("現在の§bレベル§r: $level\n現在の§e経験値§r: $exp XP (次のレベルアップまで: $maxexp xp必要です。)\n§6V§bN§eCoin§r: $money\n§aMAZAI§r: $mazai\nRank: $rank\nタグ: $tag\n参加した日: $firstlogin\n最終ログイン日: $lastlogin"))
				->addElement(new Label("---=== §6FFA§cPvP §r===---\n§eキル数§r: $ffapvp_kill\n§cデス数§r: $ffapvp_death\n§eK§7/§cD§r: $ffapvp_kd"))
				->addElement(new Label("---=== §bSpeed§aCore§cPvP §r===---\n§eキル数§r: $speedcorepvp_kill\n§cデス数§r: $speedcorepvp_death\n§aコア§c破壊回数§r: $speedcorepvp_breakcore\n§aWin§r: $speedcorepvp_win\n§cLose§r: $speedcorepvp_lose\n§eK§7/§cD§r: $speedcorepvp_kd\n§aW§7/§cL§r: $speedcorepvp_wl"))
				->addElement(new Label("---=== §aSurvival §r===---\n§eキル数§r: $survival_kill\n§cデス数§r: $survival_death\n§aブロック§c破壊数§r: §s$survival_breakblock\n§aブロック§e設置数§r: $survival_place\n§a採掘した§bダイヤモンド§aの数§r: $survival_breakdiamond\n§a採掘した§e金鉱石§aの数§r: $survival_breakgold\n§a採掘した§7鉄鉱石§aの数§r: $survival_breakiron\n§a採掘した§0石炭鉱石§aの数§r: $survival_breakcoal"))
				->sendToPlayer($sender);
			return true;
		}
		$sender->sendMessage(TextFormat::RED . "このコマンドはプレイヤーのみが実行できます。");
		return true;
	}
}
