<?php
namespace tokyo\pmmp\libform;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use tokyo\pmmp\libform\element\Dropdown;
use tokyo\pmmp\libform\element\StepSlider;
use tokyo\pmmp\libform\form\CustomForm;

class EventListener implements Listener {

  public function onReceive(DataPacketReceiveEvent $event) {
    $pk = $event->getPacket();
    if ($pk instanceof ModalFormResponsePacket) {
      $player = $event->getPlayer();
      $formId = $pk->formId;
      $response = json_decode($pk->formData, true);
      if (($form = FormApi::getForm($formId)) !== null) {
        if (!$form->isRecipient($player)) {
          return false;
        }
        $callable = $form->getCallable();
        if ($callable !== null) {
          if ($form instanceof CustomForm) {
            $response = $this->responseInt2Str($form, $response);
          }
          $callable($player, $response);
        }
        FormApi::removeForm($formId);
        $event->setCancelled();
      }
    }
  }

  public function onQuit(PlayerQuitEvent $event) {
    $player = $event->getPlayer();
    foreach (FormApi::getForms() as $formId => $form) {
      if ($form->isRecipient($player)) {
        FormApi::removeForm($formId);
        break;
      }
    }
  }

  private function responseInt2Str(CustomForm $form, array $response = null): ?array {
    if ($response !== null) {
      $elements = $form->getElements();
      foreach ($elements as $key => $element) {
        switch (true) {
          case $element instanceof Dropdown:
            $str = $element->getOption($response[$key]);
            $response[$key] = $str;
            break;

          case $element instanceof StepSlider:
            $str = $element->getStep($response[$key]);
            $response[$key] = $str;
            break;
        }
      }
    }
    return $response;
  }
}