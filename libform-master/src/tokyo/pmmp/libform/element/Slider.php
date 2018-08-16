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

// libform
use tokyo\pmmp\libform\{
  form\Form
};

/**
 * SliderClass
 */
class Slider extends Element {

  /** @var string */
  protected const ELEMENT_NAME = "slider";

  /** @var number */
  protected $min = 0;
  /** @var number */
  protected $max = 0;
  /** @var number */
  protected $step = 0;
  /** @var number */
  protected $defaultValue;

  /**
   * @param string $text
   * @param number $min
   * @param number $max
   * @param int $step
   */
  public function __construct(string $text, $min, $max, $step = 0) {
    if ($min > $max) throw new \InvalidArgumentException("Minimum value must be less than maximum value");
    parent::__construct($text);
    $this->min = $min;
    $this->max = $max;
    $this->defaultValue = $min;
    $this->setStep($step);
  }

  public function setStep($step): Slider {
    if ($step < 0) throw new \InvalidArgumentException("The value of the step must be a positive value");
    $this->step = $step;
    return $this;
  }

  public function setDefaultValue($value): Slider {
    if ($value < $this->min || $value > $this->max) {
      throw new \InvalidArgumentException("The default value must be between the minimum and maximum values");
    }
    $this->defaultValue = $value;
    return $this;
  }

  final public function format(): array {
    $data = [
      Form::KEY_TYPE => self::ELEMENT_NAME,
      Form::KEY_TEXT => $this->text,
      Form::KEY_MIN => $this->min,
      Form::KEY_MAX => $this->max,
    ];
    if ($this->step > 0) $data[Form::KEY_STEP] = $this->step;
    if ($this->defaultValue !== $this->min) {
      $data[Form::KEY_DEFAULT] = $this->defaultValue;
    }
    return $data;
  }
}
