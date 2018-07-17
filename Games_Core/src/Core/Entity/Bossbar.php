<?php

namespace Core\Entity;

use pocketmine\{
    entity\Attribute,
    entity\Entity,
    entity\EntityIds,
    math\Vector3,
    network\mcpe\protocol\AddEntityPacket,
    network\mcpe\protocol\BossEventPacket,
    network\mcpe\protocol\SetEntityDataPacket,
    network\mcpe\protocol\UpdateAttributesPacket,
    Player
};

class Bossbar extends Vector3
{
    protected $healthPercent = 0;
    protected $maxHealthPercent = 1;
    protected $entityId;
    protected $metadata = [];
    protected $viewers = [];

    /**
     * Bossbar constructor.
     * @param string $title
     * @param float $hp
     * @param float $maxHp
     */
    public function __construct(string $title, float $hp = 1, float $maxHp = 1)
    {
        parent::__construct(0, 0, 0);
        $flags = (
            (1 << Entity::DATA_FLAG_INVISIBLE) |
            (1 << Entity::DATA_FLAG_IMMOBILE)
        );
        $this->metadata = [
            Entity::DATA_FLAGS => [Entity::DATA_TYPE_LONG, $flags],
            Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $title]];
        $this->setHealthPercent($hp, $maxHp);
        $this->entityId = Entity::$entityCount++;
    }

    /**
     * @param float|null $hp
     * @param float|null $maxHp
     * @param bool $update
     */
    public function setHealthPercent(float $hp = null, float $maxHp = null, bool $update = true)
    {
        if ($maxHp !== null) {
            $this->maxHealthPercent = $maxHp;
        }
        if ($hp !== null) {
            if ($hp > $this->maxHealthPercent) {
                $this->maxHealthPercent = $hp;
            }
            $this->healthPercent = $hp;
        }
        if ($update) {
            $this->BossbarUpdateAll();
        }
    }

    /**
     * @param Player $player
     * @param bool $isViewer
     */
    public function sendBar(Player $player, bool $isViewer = true)
    {
        $pk = new AddEntityPacket;
        $pk->entityRuntimeId = $this->entityId;
        $pk->type = EntityIds::SHULKER;
        $pk->metadata = $this->metadata;
        $pk->position = $this;
        $player->dataPacket($pk);
        $player->dataPacket($this->getHealthPacket());
        $pk2 = new BossEventPacket;
        $pk2->bossEid = $this->entityId;
        $pk2->eventType = BossEventPacket::TYPE_SHOW;
        $pk2->title = $this->getMetadata(Entity::DATA_NAMETAG);
        $pk2->healthPercent = $this->healthPercent;
        $pk2->overlay = 0;
        $pk2->unknownShort = 0;
        $pk2->color = 0;
        $player->dataPacket($pk2);
        if ($isViewer) {
            $this->viewers[$player->getLoaderId()] = $player;
        }
    }

    /**
     * @param Player $player
     */
    public function BossbarUpdate(Player $player)
    {
        $pk = new BossEventPacket;
        $pk->bossEid = $this->entityId;
        $pk->eventType = BossEventPacket::TYPE_TITLE;
        $pk->healthPercent = $this->healthPercent;
        $pk->title = $this->getMetadata(Entity::DATA_NAMETAG);
        $pk2 = clone $pk;
        $player->dataPacket($pk);
        $pk2->eventType = BossEventPacket::TYPE_HEALTH_PERCENT;
        $player->dataPacket($pk2);
        $player->dataPacket($this->getHealthPacket());
        $mpk = new SetEntityDataPacket;
        $mpk->entityRuntimeId = $this->entityId;
        $mpk->metadata = $this->metadata;
        $player->dataPacket($mpk);
    }
    public function BossbarUpdateAll() : void
    {
        foreach ($this->viewers as $player) {
            $this->BossbarUpdate($player);
        }
    }
    protected function getHealthPacket() : UpdateAttributesPacket
    {
        $attr = Attribute::getAttribute(Attribute::HEALTH);
        $attr->setMaxValue($this->maxHealthPercent);
        $attr->setValue($this->healthPercent);
        $pk = new UpdateAttributesPacket;
        $pk->entityRuntimeId = $this->entityId;
        $pk->entries = [$attr];
        return $pk;
    }
    public function getMetadata(int $key)
    {
        return isset($this->metadata[$key]) ? $this->metadata[$key][1] : null;
    }
}
