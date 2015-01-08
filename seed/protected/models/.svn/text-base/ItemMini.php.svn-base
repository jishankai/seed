<?php

class ItemMini extends ConfigModel {

    public $id;

    public function __construct($id) {
        parent::__construct($id);
        $this->id = $id;
        $this->attachBehavior('ItemAttributes', new ItemAttributes);
    }

    public static function attributeColumns() {
        return array(
            'itemType', 'name', 'description', 'type', 'price', 'sellPrice', 'num', 'image', 'moneyType', 'category', 'level', 'canSell',
        );
    }

}

?>