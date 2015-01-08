<?php

class UseItem extends ItemMini {

    public function __construct($id) {
        parent::__construct($id);
        self::getConfig();
    }

    public static function attributeColumns() {
        $arr = parent::attributeColumns();
        //array_push($arr,'');
        return $arr;
    }

    public function getConfig() {
        $this->name = parent::__get('name');
        $this->description = parent::__get('description');
        $this->category = parent::__get('category');
        $this->moneyType = parent::__get('moneyType');
        $this->type = parent::__get('type');
        $this->price = parent::__get('price');
        $this->sellPrice = parent::__get('sellPrice');
        $this->image = parent::__get('image');
        $this->effect = parent::__get('effect');
        $this->canSell = parent::__get('canSell');
    }

}

?>
