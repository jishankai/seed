<?php

class ItemMeta extends ConfigModel {

    public $id;
    public $itemObject;

    //public $itemType;

    public function __construct($id) {
        parent::__construct($id);
        $this->id = $id;
        $class = $this->getClassByType($this->itemType);
        $this->itemObject = Yii::app()->objectLoader->load($class, $id);
        $this->attachBehavior('ItemUseEffect', new ItemUseEffect);
    }

    public function getClassByType($type) {
        switch ($type) {
            case 'DecoItem':
            case 'ResItem':
            case 'UseItem':
            case 'ChestItem':
                return ucfirst($type);
        }
    }

    public function getItemType() {
        return $this->itemType;
    }

    public function getName() {
        return $this->itemObject->name;
    }

    public function getDescription() {
        return $this->itemObject->description;
    }

    public function getType() {
        return $this->itemObject->type;
    }

    public function getPrice() {
        return $this->itemObject->price;
    }

    public function getSellPrice() {
        return $this->itemObject->sellPrice;
    }

    public function getNum() {
        return $this->itemObject->num;
    }

    public function getImage() {
        return $this->itemObject->image;
    }

    public function getImagePath() {
        return 'images/item/' . $this->itemObject->image . '?v=' . SeedVersion::getVersion();
    }

    public function getIcoPath() {
        //return $this->itemObject->image;
    }

    public function getMidImagePath() {
        return 'images/middleItem/' . $this->itemObject->image . '?v=' . SeedVersion::getVersion();
    }

    public function getSmallImagePath() {
        return 'images/smallItem/' . $this->itemObject->image . '?v=' . SeedVersion::getVersion();
    }

    public function getMoneyType() {
        return $this->itemObject->moneyType;
    }

    public function getCategory() {
        return $this->itemObject->category;
    }

    public static function getAll() {
        return static::getMultiData();
    }

}

?>
