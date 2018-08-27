<?php

namespace Core\enemys;

class Human extends Entity 
{
    public function __construct(Level $level, CompoundTag $nbt) {
        parent::__construct($level, $nbt);
        $this->uuid = UUID::fromRandon();
        $this->skin = new Skin();
    }

    public function getName() : string{
        return $this->getNameTag();
    }

    public function getUniqueId() : ?UUID{
        return $this->uuid;
    }

    public function setSkin(Skin $newSkin) {
        $this->skin = $skin;
    }

    public function sendSkin(?array $targets = \null) : void{
        $pk = new PlayerSkinPacket();
        $pk->uuid = $this->getUniqueId();
        $pk->skin = $this->skin;
        $this->server->broadcastPacket($targets ?? $this->hasSpawned, $pk);
    }

    protected function sendSpawnPacket(Player $player) : void{

        $this->sendPlayerListPacket($player, PlayerListPacket::TYPE_ADD);

        $pk = new AddPlayerPacket();
        $pk->uuid = $this->getUniqueId();
        $pk->username = $this->getName();
        $pk->entityRuntimeId = $this->getId();
        $pk->position = $this->asVector3();
        $pk->motion = $this->getMotion();
        $pk->yaw = $this->yaw;
        $pk->pitch = $this->pitch;
        $pk->item = $this->getInventory()->getItemInHand();
        $pk->metadata = $this->propertyManager->getAll();
        $player->dataPacket($pk);

        //TODO: Hack for MCPE 1.2.13: DATA_NAMETAG is useless in AddPlayerPacket, so it has to be sent separately
        $this->sendData($player, [self::DATA_NAMETAG => [self::DATA_TYPE_STRING, $this->getNameTag()]]);

        $this->sendPlayerListPacket($player, PlayerListPacket::TYPE_REMOVE);
    }

    public function sendPlayerListPacket(Player $player, int $type) {
        $pk = new PlayerListPacket();
        $pk->type = $type;
        switch($type) {
            case PlayerListPacket::TYPE_REMOVE:
                $pk->entries = [PlayerListEntry::createRemovalEntry($this->uuid)];
                break;
            case PlayerListPacket::TYPE_ADD:
                $pk->entries = [PlayerListEntry::createAdditionEntry($this->uuid, $this->id, $this->getName(), $this->getName(), 0, $this->skin)];
                break;
            default:$pk->entries = [];break;
        }
        $player->dataPacket($pk);
    }

    protected function initEntity() : void{
        if($this->namedtag->hasTag("NameTag", StringTag::class)){
            $this->setNameTag($this->namedtag->getString("NameTag"));
        }
    }
    
    public function saveNBT() : void{}
}