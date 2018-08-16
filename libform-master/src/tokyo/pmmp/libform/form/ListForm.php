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

// libform
use tokyo\pmmp\libform\{
  element\Button
};

/**
 * ListFormClass
 */
class ListForm extends Form implements \JsonSerializable {

  /** @var string */
  private const FORM_TYPE = "form";
  /** @var array */
  protected $data = [
    Form::KEY_TYPE => self::FORM_TYPE,
    Form::KEY_TITLE => "",
    Form::KEY_CONTENT => "",
    Form::KEY_BUTTONS => []
  ];

  public function getContent(): string {
    return $this->data[Form::KEY_CONTENT];
  }

  public function setContent(string $content): ListForm {
    $this->data[Form::KEY_CONTENT] = $content;
    return $this;
  }

  public function getButtons(): array {
    return $this->data[Form::KEY_BUTTONS];
  }

  public function addButton(Button $button): ListForm {
    $this->data[Form::KEY_BUTTONS][] = $button;
    return $this;
  }

  final public function jsonSerialize(): array {
    $data = $this->data;
    if (!empty(($buttons = $this->getButtons()))) {
      unset($data[Form::KEY_BUTTONS]);
      foreach ($this->getButtons() as $button) {
        $data[Form::KEY_BUTTONS][] = $button->format();
      }
    }
    return $data;
  }
}
