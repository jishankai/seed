<?php

class ItemUseEffect extends CBehavior {

    private $isAdd = false;

    public function setEffect($item, $userId) {
        $this->addBehavior();
        return $this->parseEffect($this->getOwner(), $item, $userId);
    }

    public function checkItems($item, $userId) {
        $this->addBehavior();
        return true;
    }

    private function addBehavior() {
        $id = $this->getOwner()->id;

        if ($this->isAdd)
            return true;
        $defaultClass = 'ItemUseEffects';
        $ItemChestUse = 'ItemChestUse';
        $ItemMaterialBagUse = 'ItemMaterialBagUse';
        $ItemActionPointUse = 'ItemActionPointUse';
        $ItemStampUse = 'ItemStampUse';
        $effectClass = 'ItemUseEffect' . $id;
        $effectFile = dirname(__FILE__) . '/itemUse/' . $effectClass . '.php';
        if (file_exists($effectFile)) {
            include_once $effectFile;
            $this->attachBehavior('ItemUseEffect', new $effectClass);
        } else if ($id == 47) {
            $effectFile = dirname(__FILE__) . '/itemUse/' . $ItemChestUse . '.php';
            include_once $effectFile;
            $this->attachBehavior('ItemUseEffect', new $ItemChestUse);
        } else if ($id >= 39 && $id <= 41) {
            $effectFile = dirname(__FILE__) . '/itemUse/' . $ItemMaterialBagUse . '.php';
            include_once $effectFile;
            $this->attachBehavior('ItemUseEffect', new $ItemMaterialBagUse);
        }else if ($id >= 37 && $id <= 38) {
            $effectFile = dirname(__FILE__) . '/itemUse/' . $ItemActionPointUse . '.php';
            include_once $effectFile;
            $this->attachBehavior('ItemUseEffect', new $ItemActionPointUse);
        }else if ($id == 34 || $id == 35) {
            $effectFile = dirname(__FILE__) . '/itemUse/' . $ItemStampUse . '.php';
            include_once $effectFile;
            $this->attachBehavior('ItemUseEffect', new $ItemStampUse);
        } else {
            $effectFile = dirname(__FILE__) . '/itemUse/' . $defaultClass . '.php';
            include_once $effectFile;
            $this->attachBehavior('ItemUseEffect', new $defaultClass);
        }
        $this->isAdd = true;
    }

}
