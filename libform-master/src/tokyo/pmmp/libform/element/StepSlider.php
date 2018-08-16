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
 * StepSliderClass
 */
class StepSlider extends Element {

  /** @var string */
  protected const ELEMENT_NAME = "step_slider";

  /** @var string[] */
  protected $steps = [];
  /** @var int */
  protected $defaultKey = 0;

  public function __construct(string $text, array $steps = []) {
    parent::__construct($text);
    $this->steps = $steps;
  }

  public function getStep(int $key): ?string {
    return isset($this->steps[$key])? $this->steps[$key] : null;
  }

  public function addStep(string $stepText, bool $isDefault = false): StepSlider {
    if ($isDefault) $this->defaultKey = count($this->steps);
    $this->steps[] = $stepText;
    return $this;
  }

  public function removeStep(string $stepText): StepSlider {
    $flip = array_flip($this->steps);
    if (array_key_exists($stepText, $flip)) {
      $key = $flip[$stepText];
      unset($this->steps[$key]);
    }
    return $this;
  }

  public function getDefaultStep(): string {
    return empty($this->steps)? "" : $this->steps[$this->defaultKey];
  }

  public function setDefaultStep(string $stepText): StepSlider {
    $flip = array_flip($this->steps);
    if (array_key_exists($stepText, $flip)) {
      $key = $flip[$stepText];
      $this->defaultKey = $key;
    }else {
      throw new \OutOfRangeException("Invalid step text " . $stepText);
    }
    return $this;
  }

  public function format(): array {
    $data = [
      Form::KEY_TYPE => self::ELEMENT_NAME,
      Form::KEY_TEXT => $this->text,
      Form::KEY_STEPS => $this->steps,
      Form::KEY_DEFAULT => $this->defaultKey
    ];
    return $data;
  }
}
