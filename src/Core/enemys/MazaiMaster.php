<?php

namespace Core\enemys;

class MazaiMaster extends Human
{
    public function __construct(Level $level, CompoundTag $nbt, string $skin)
    {
        parent::__construct($level, $nbt);
        $this->money = new Money();
        $this->level = new Level();
        $this->mazai = new MazaiPoint();
        $this->skin = new Skin("Standard_Custom", base64_decode(file_get_contents("plugins/Games_Core/resources/skins/$skin")))
    }

    public function interact(Player $player) {
        FormApi::makeListForm(function (Player $player, ?int $key) {
            if (!FormApi::formCancelled($key)) {
                switch ($key) {
                    case 0:
                        if ($this->mazai->reduceMazai($player->getName(), 1)) {
                            $player->sendMessage(MessagesEnum::MAZAI_SUCCESS);
                            $this->level->addExp($player->getName(), 300);
                            Main::$instance->getScheduler()->scheduleDelayedTask(new LevelCheckingTask(Main::$instance, $player), 20);
                        } else {
                            $player->sendMessage(MessagesEnum::MAZAI_ERROR);
                        }
                        break;
                    case 1:
                        if ($this->mazai->reduceMazai($player->getName(), 1)) {
                            $player->sendMessage(MessagesEnum::MAZAI_SUCCESS);
                            $this->money->addMoney($player->getName(), 10000);
                        } else {
                            $player->sendMessage(MessagesEnum::MAZAI_ERROR);
                        }
                        break;
                }
            }
        })->setTitle("§aMAZAI§e変換所")
            ->setContent("§aMAZAI§rを色んなものに変換します。")
            ->addButton(new Button("§e300§aXP\n§e1§aMAZAI"))
            ->addButton(new Button("§e10000§6V§bN§eCoin\n§e1§aMAZAI\""))
            ->sendToPlayer($player);
    }
}