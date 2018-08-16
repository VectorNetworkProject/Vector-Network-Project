<?php

/**
 * // English
 *
 * libform is a library for PocketMine-MP for easy operation of forms
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * This software is distributed under "MIT license".
 * You should have received a copy of the MIT license
 * along with this program.  If not, see
 * < https://opensource.org/licenses/mit-license >.
 *
 * ---------------------------------------------------------------------
 * // 日本語
 *
 * libformは、フォームを簡単に操作するためのpocketmine-MP向けライブラリです
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace tokyo\pmmp\libform\form;

// pocketmine
use pocketmine\{
  Player,
  network\mcpe\protocol\ModalFormRequestPacket
};

/**
 * abstractFormClass
 */
abstract class Form implements \JsonSerializable {

  /** @var string */
  public const KEY_TYPE = "type";
  public const KEY_CONTENT = "content";
  public const KEY_DATA = "data";
  public const KEY_IMAGE = "image";
  public const KEY_TITLE = "title";
  public const KEY_TEXT = "text";
  public const KEY_OPTIONS = "options";
  public const KEY_DEFAULT = "default";
  public const KEY_PLACEHOLDER = "placeholder";
  public const KEY_STEP = "step";
  public const KEY_STEPS = "steps";
  public const KEY_BUTTON1 = "button1";
  public const KEY_BUTTON2 = "button2";
  public const KEY_BUTTONS = "buttons";
  public const KEY_MIN = "min";
  public const KEY_MAX = "max";

  /** @var int */
  protected $id = 0;
  /** @var ?callable */
  private $callable = null;
  /** @var array */
  protected $data = [];
  /** @var string */
  protected $playerName = "";

  public function __construct(int $id, callable $callable = null) {
    $this->id = $id;
    $this->callable = $callable;
  }

  /**
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

  /**
   * @param  int $id
   * @return Form
   */
  public function setId(int $id): Form {
    $this->id = $id;
    return $this;
  }

  /**
   * @return string
   */
  public function getTitle(): string {
    return $this->data[self::KEY_TITLE];
  }

  /**
   * @param  string $title
   * @return Form
   */
  public function setTitle(string $title): Form {
    $this->data[self::KEY_TITLE] = $title;
    return $this;
  }

  /**
   * @return ?callable
   */
  public function getCallable(): ?callable {
    return $this->callable;
  }

  /**
   * @param  Player $player
   * @return bool
   */
  public function isRecipient(Player $player): bool {
    return $player->getName() === $this->playerName;
  }

  /**
   * @param Player $player
   * @return Form
   */
  public function sendToPlayer(Player $player): Form {
    $pk = new ModalFormRequestPacket;
    $pk->formId = $this->id;
    $pk->formData = json_encode($this);
    $this->playerName = $player->getName();
    $player->dataPacket($pk);
    return $this;
  }

  /**
   * @return array
   */
  abstract public function jsonSerialize(): array;
}
