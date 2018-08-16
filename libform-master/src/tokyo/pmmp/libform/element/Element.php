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

namespace tokyo\pmmp\libform\element;

/**
 * AbstractElementClass
 */
abstract class Element {

  /** @var string */
  protected const ELEMENT_NAME = "";

  /** @var string */
  protected $text = "";

  public function __construct(string $text) {
    $this->text = $text;
  }

  abstract public function format(): array;
}
