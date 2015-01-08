<?php

class ShopGoodsEffect extends CBehavior{

    public function __construct($goodsType) {
        $effectClass = 'ShopGoods'.ucfirst($goodsType) ;
        $effectFile = dirname(__FILE__).'/ShopGoods/'.$effectClass.'.php' ;
        if( file_exists($effectFile) ) {
            include_once $effectFile ;
            $this->attachBehavior($effectClass,new $effectClass);
        }
        else {
            throw new CException('file not exists.'.$effectFile);
        }
    }

    public function setEffect($player,$number) {
        return $this->parseEffect($player,$number) ;
    }

    public function sendEffect($fromPlayer,$toPlayer,$number) {
        $this->checkFriend( $fromPlayer->playerId,$toPlayer->playerId );
        return $this->parseSendEffect($fromPlayer,$toPlayer,$number) ;
    }

    public function checkGoods($player) {
        $goodsId = $this->getOwner()->goodsId;
        $price = $this->getPrice() ;
        if( $this->getOwner()->moneyType==1 ) {
            $playerMoney = Yii::app()->objectLoader->load('PlayerMoney',$player->playerId);
            $playerMoney -> checkEnough( $price );
        }
        else {
            if( !$player->checkGold( $price ) ){
                //throw new SException(Yii::t('PlayerAttributes', 'Gold are not enough!'));
                throw new SException('gold is not enough', EXCEPTION_TYPE_GOLD_NOT_ENOUGH);
            }
        }
        /*
        if (isset($this->checkGoodsList[$goodsId]) && $this->checkGoodsList[$goodsId]) {
            $this->checkNeedUseGoods($player);
        }
        */
    }

    public function getName() {
        return isset($this->getOwner()->goodsName)?$this->getOwner()->goodsName:$this->getMyName();
    }

    public function getPrice() {
        return isset($this->getOwner()->price)?$this->getOwner()->price:$this->getMyPrice();
    }
    public function getDesc() {
        return isset($this->getOwner()->desc)?$this->getOwner()->desc:$this->getMyDesc();
    }

    public function getImage() {
        return isset($this->getOwner()->image)?$this->getOwner()->image:$this->getMyImage();
    }

    public function checkFriend( $playerId1,$playerId2 ) {
        if( PlayerFriend::isFriend($playerId1, $playerId2)!=0 ){
            throw new SException( Yii::t('Firend','You do not have the friend') );
        }
    }

    public function getMaxBuyNum( $playerId ) {
        $defaultMax = 999 ;
        if( $this->getOwner()->goodsType == 'item' ) {
            $item = Yii::app()->objectLoader->load( 'Item',$playerId );
            $itemMeta = Yii::app()->objectLoader->load( 'ItemMeta',$this->getOwner()->internalId );
            $num = $item->getSurplusPosition($itemMeta) ;
            return !empty($num)?$num:$defaultMax;   
        }
        else {
            return $defaultMax ;
        }
    }

}
