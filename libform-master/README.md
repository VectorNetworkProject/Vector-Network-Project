## libform

### Overview
Select language: [English](#en_US), [日本語](#ja_JP)

***
<a name="en_US"></a>
# English

## libform
It is a virion library that can handle various forms easily.

### How to use

#### 1. Register formApi
```php
use tokyo\pmmp\libform\FormApi;

// ex)
public function onEnable() {
  FormApi::register($this);
}
```

#### 2. Form
#### 2-1. ModalForm
```php
use tokyo\pmmp\libform\FormApi;

$modal = FormApi::makeModalForm();
$modal->setTitle("ModalForm")
  ->setContent("Yes, or No?")
  ->setButtonText(true, "Yes")// If omitted, it'll be an empty string
  ->setButtonText(false, "No")// If omitted, it'll be an empty string
  ->sendToPlayer($player);// send!!
```

#### 2-2. CustomForm
```php
use tokyo\pmmp\libform\FormApi;
# Elements
use tokyo\pmmp\libform\element\Dropdown;
use tokyo\pmmp\libform\element\Input;
use tokyo\pmmp\libform\element\Label;
use tokyo\pmmp\libform\element\Slider;
use tokyo\pmmp\libform\element\StepSlider;
use tokyo\pmmp\libform\element\Toggle;

$custom = FormApi::makeCustomForm();
$custom->setTitle("CustomForm")
  ->addEleemnt(new Dropdown("dropdown", ["a", "b", "c"]))
  ->addElement(new Input("Input", "Placeholder", "defaultValue"))
  ->addElement(new Label("Label"))
  ->addElement(new Slider("Slider", 0, 100, 1))// Min, Max, Interval
  ->addElement(new StepSlider("StepSlider", ["Awesome!", "Good", "Bad"]))
  ->addElement(new Toggle("Toggle", true))// 2nd argument is a default value
  ->sendToPlayer($player);
```

### 2-3. ListForm
```php
use tokyo\pmmp\libform\FormApi;
# Elements
use tokyo\pmmp\libform\element\Button;

$list = FormApi::makeListForm();
$list->setTitle("ListForm")
  ->setContent("Choose one!")
  ->addButton(new Button("Normal Button"))
  ->addButton((new Button("Picture Button"))->setImage("URL", Button::IMAGE_TYPE_URL))
  // IMAGE_TYPE_PATH is not working
  //->addButton((new Button("Picture Button"))->setImage("Path", Button::IMAGE_TYPE_PATH))
  ->sendToPlayer($player);
```



#### 3. Get responses
#### 3-1. ModalForm
#### 3-1-1. callable
```php
$modal = FormApi::makeModalForm([$this, "callback"]);// class, functionName

/**
 * Receive response from ModalForm
 * @param Player $player
 * @param ?bool $response
 */
public function callback(Player $player, ?bool $response): void {
  if (FormApi::FormCancelled($response)) {
    // if cancelled
  }else {
    // if not cancelled
  }
}
```

#### 3-1-2. closure
```php
$modal = FormApi::makeModalForm(function(Player $player, ?bool $response) {
  if (FormApi::FormCancelled($response)) {
    // if cancelled
  }else {
    // if not cancelled
  }
});
```

#### 3-2. CustomForm
#### 3-2-1. callable
```php
$modal = FormApi::makeCustomForm([$this, "callback"]);// class, functionName

/**
 * Receive response from CustomForm
 * @param Player $player
 * @param ?array $response
 */
public function callback(Player $player, ?array $response): void {
  if (FormApi::FormCancelled($response)) {
    // if cancelled
  }else {
    // if not cancelled
  }
}
```

#### 3-2-2. closure
```php
$modal = FormApi::makeCustomForm(function(Player $player, ?array $response) {
  if (FormApi::FormCancelled($response)) {
    // if cancelled
  }else {
    // if not cancelled
  }
});
```

#### 3-3. ListForm
#### 3-3-1. callable
```php
$modal = FormApi::makeListForm([$this, "callback"]);// class, functionName

/**
 * Receive response from ListForm
 * @param Player $player
 * @param ?int $response
 */
public function callback(Player $player, ?int $key): void {
  if (FormApi::FormCancelled($response)) {
    // if cancelled
  }else {
    // if not cancelled
  }
}
```

#### 3-3-2. closure
```php
$modal = FormApi::makeListForm(function(Player $player, ?int $key) {
  if (FormApi::FormCancelled($response)) {
    // if cancelled
  }else {
    // if not cancelled
  }
});
```

***
<a name="ja_JP"></a>
# 日本語

## libform
様々なフォームを簡単に扱うためのvirionライブラリです。

### 使い方

#### フォームAPI登録
必要なuse
```php
use tokyo\pmmp\libform\{
  FormApi
};
```
どこでもいいので以下のコードを実行します  
例ではPluginBase::onEnable()で実行しています
```php
public function onEnable() {
  FormAPI::register($this);
}
```
#### モダルフォーム
必要なuse
```php
use tokyo\pmmp\libform\{
  FormApi
};
```
モダルフォームは"はい"か"いいえ"のような二択で答えるものです
```php
// $this(このクラス)の"test"という名の関数をフォーム入力後に呼び出します
$modal = FormApi::makeModalForm([$this, "test"]);
$modal->setTitle("モダルフォーム")
->setContent("内容")
->setButtonText(true, "はい")// true => 上側
->setButtonText(false, "いいえ")// false => 下側
->sendToPlayer($sender);// プレイヤーに送信
```

#### カスタムフォーム
必要なuse
```php
use tokyo\pmmp\libform\{
  FormApi,
  element\Dropdown,
  element\Input,
  element\Label,
  element\Slider,
  element\StepSlider,
  element\Toggle
};
```
カスタムフォームは多彩なエレメントを使用して様々な使用用途に適すものです
```php
// $this(このクラス)の"test"という名の関数をフォーム入力後に呼び出します
$custom = FormApi::makeCustomForm([$this, "test"]);
$custom->setTitle("カスタムフォーム")
->addElement(new Dropdown("ドロップダウン", ["あ", "い", "う"]))
->addElement(new Input("インプット", "プレースホルダー", "デフォルト値"))
->addElement(new Label("ラベル"))
->addElement(new Slider("スライダー", 0, 10, 1))// 最小値, 最大値, 間隔 の順です
->addElement(new StepSlider("ステップスライダー", ["だめ", "ふつう", "イイね！"]))
->addElement(new Toggle("トグル", true))// 第二引数は初期値です
->sendToPlayer($sender);// プレイヤーに送信
```

#### リストフォーム
必要なuse
```php
use tokyo\pmmp\libform\{
  FormApi,
  element\Button
};
```
リストフォームは複数の選択肢の中から一つのものを選択するフォームです
```php
// $this(このクラス)の"test"という名の関数をフォーム入力後に呼び出します
$list = FormAPI::makeListForm([$this, "test"]);
$list->setTitle("リストフォーム")
->setContent("内容")
->addButton((new Button("ボタン1"))->setImage("画像ファイルのURL", Button::IMAGE_TYPE_URL))
// パスからは現在画像を読み込むことができません
// ->addButton((new Button("ボタン2"))->setImage("画像ファイルのパス", Button::IMAGE_TYPE_PATH))
->sendToPlayer($sender);
```

#### フォームの返り値の取得
上の例では`[$this, "test"]`を例として挙げたので、その通りにやってみましょう
```php
/**
 * @description
 * コールバック関数なのでpublicでなければなりません
 * @param  mixed $response フォーム返り値,キャンセルされた場合はnullが帰ります
 * @return void            この関数の返り値,なんでもいいです
 */
public function test($response): void {
  if (FormApi::FormCancelled($response)) {
    // formがキャンセルされていれば
  }else {
    // formがキャンセルされていなければ
  }
}
```
