<?php

class ShopGoods extends ConfigModel{
    public $goodsId ;
    
    public function __construct($goodsId) {
        $this->goodsId = $goodsId ;
        parent::__construct($goodsId);
        $this->attachbehavior('ShopGoodsEffect', new ShopGoodsEffect($this->goodsType));
    }

    public static function getCategoryGoods( $category ){
        $params = array('category'=>$category);
        return static::getMultiData($params);
    }

    public function getMoneyName() {
        return $this->moneyType==1?'gold':'money';
    }

}
