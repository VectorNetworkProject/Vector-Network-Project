<?php

namespace Core\enemys;

class Skin {

    private $skinId;
    private $skinData;
    private $capeData;
    private $geometryName;
    private $geometryData;

    public function __construct(string $skinId = "", string $skinData = "", string $capeData = "", string $geometryName = "", string $geometryData = ""){
        $this->skinId = $skinId;
        $this->skinData = $skinData;
        $this->capeData = $capeData;
        $this->geometryName = $geometryName;
        $this->geometryData = $geometryData;
    }

    public function getSkinId() : string{
        return $this->skinId;
    }

    public function setSkinId(string $newSkinId)  {
        $this->skinId = $newSkinId;
    }

    public function getSkinData() : string{
        return $this->skinData;
    }

    public function setSkinData(string $newSkinData) {
        $this->skinData = $newSkinData;
    }

    public function getCapeData() : string{
        return $this->capeData;
    }

    public function setCapeData(string $newCapeData) {
        $this->capeData = $newCapeData;
    }

    public function getGeometryName() : string{
        return $this->geometryName;
    }

    public function setGeometryName(string $newGeometryName) {
        $this->geometryName = $newGeometryName;
    }

    public function getGeometryData() : string{
        return $this->geometryData;
    }

    public function setGeometryData(string $newGeometryData) {
        $this->geometryData = $newGeometryData;
    }

    public function debloatGeometryData() : void{
        if($this->geometryData !== ""){
            $this->geometryData = (string) \json_encode(\json_decode($this->geometryData));
        }
    }
}