<?php

class DecoItem extends ItemMini {

    public function __construct($id) {
        parent::__construct($id);
        self::getConfig();
    }

    public static function attributeColumns() {
        $arr = parent::attributeColumns();
        array_push($arr, 'direction');
        array_push($arr, 'sizeX');
        array_push($arr, 'sizeY');
        array_push($arr, 'grow');
        array_push($arr, 'special');
        array_push($arr, 'sizeType');
        return $arr;
    }

    public function getConfig() {
        $this->image = parent::__get('image');
        $this->type = parent::__get('type');
        $this->sizeX = parent::__get('sizeX');
        $this->sizeY = parent::__get('sizeY');
        $this->name = parent::__get('name');
        $this->grow = parent::__get('grow');
        $this->price = parent::__get('price');
        $this->sellPrice = parent::__get('sellPrice');
        $this->category = parent::__get('category');
        $this->description = parent::__get('description');
        $this->moneyType = parent::__get('moneyType');
        $this->special = parent::__get('special');
        $this->sizeType = parent::__get('sizeType');
        $this->level = parent::__get('level');
        $this->canSell = parent::__get('canSell');
    }

}

?>
