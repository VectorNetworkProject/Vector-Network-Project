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
  element\Element
};

/**
 * CustomForm
 */
class CustomForm extends Form implements \JsonSerializable {

  /** @var string */
  private const FORM_TYPE = "custom_form";
  /** @var array */
  protected $data = [
    Form::KEY_TYPE => self::FORM_TYPE,
    Form::KEY_TITLE => "",
    Form::KEY_CONTENT => []
  ];

  public function getElements(): array {
    return $this->data[Form::KEY_CONTENT];
  }

  public function getElement(int $key): ?Element {
    if (array_key_exists($key, $this->data[Form::KEY_CONTENT])) {
      return $this->data[Form::KEY_CONTENT][$key];
    }
    return null;
  }

  public function addElement(Element $element): CustomForm {
    if (!($element instanceof Button)) {// Button is for a ListForm
      $this->data[Form::KEY_CONTENT][] = $element;
    }
    return $this;
  }

  final public function jsonSerialize(): array {
    $data = $this->data;
    unset($data[Form::KEY_CONTENT]);
    foreach ($this->getElements() as $element) {
      $data[Form::KEY_CONTENT][] = $element->format();
    }
    return $data;
  }
}
