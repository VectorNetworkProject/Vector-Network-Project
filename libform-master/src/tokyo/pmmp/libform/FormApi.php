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

namespace tokyo\pmmp\libform;

// pocketmine
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

// libform
use tokyo\pmmp\libform\form\CustomForm;
use tokyo\pmmp\libform\form\Form;
use tokyo\pmmp\libform\form\ListForm;
use tokyo\pmmp\libform\form\ModalForm;

/**
 * FormApiClass
 */
class FormApi {

  /** @var bool */
  private static $activated = false;
  /** @var Form[] */
  private static $forms = [];

  private function __construct() {
    // DONT USE THIS METHOD!
  }

  /**
   * @param PluginBase $plugin
   */
  public static function register(PluginBase $plugin): void {
    if (!self::$activated) {
      Server::getInstance()
        ->getPluginManager()
        ->registerEvents(new EventListener, $plugin);
    }
  }

  /**
   * Generate a custom form
   * @param  callable   $callable
   * @return CustomForm
   */
  public static function makeCustomForm(callable $callable = null): CustomForm {
    $formId = self::makeRandomFormId();
    $form = new CustomForm($formId, $callable);
    if ($callable !== null) self::$forms[$formId] = $form;
    return $form;
  }

  /**
   * Generate a list form
   * @param  callable $callable
   * @return ListForm
   */
  public static function makeListForm(callable $callable = null): ListForm {
    $formId = self::makeRandomFormId();
    $form = new ListForm($formId, $callable);
    if ($callable !== null) self::$forms[$formId] = $form;
    return $form;
  }

  /**
   * Generate a modal form
   * @param  callable  $callable
   * @return ModalForm
   */
  public static function makeModalForm(callable $callable = null): ModalForm {
    $formId = self::makeRandomFormId();
    $form = new ModalForm($formId, $callable);
    if ($callable !== null) self::$forms[$formId] = $form;
    return $form;
  }

  /**
   * Generate random formId
   * @return int formId
   */
  public static function makeRandomFormId(): int {
    return mt_rand(0, mt_getrandmax());
  }

  /**
   * @return array
   */
  public static function getForms(): array {
    return self::$forms;
  }

  /**
   * @param int $formId
   * @return Form|null
   */
  public static function getForm(int $formId): ?Form {
    if (array_key_exists($formId, self::$forms)) {
      return self::$forms[$formId];
    }else {
      return null;
    }
  }

  /**
   * @param int $formId
   */
  public static function removeForm(int $formId): void {
    if (array_key_exists($formId, self::$forms)) {
      unset(self::$forms[$formId]);
    }
  }

  /**
   * Check if the form has been canceled
   * @param  mixed $response
   * @return bool
   */
  public static function formCancelled($response): bool {
    return $response === null? true : false;
  }
}
