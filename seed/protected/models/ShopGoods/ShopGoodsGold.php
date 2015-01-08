<?php 

class ShopGoodsGold extends CBehavior {

    public function parseEffect( $player,$number=1 ) {
    }

    public function parseSendEffect( $fromPlayer,$toPlayer,$number=1 ) {
    }

    public function getMyName() {
        return Yii::t('Shop','goods_name_'.$this->getOwner()->getOwner()->goodsId);
    }

    public function getMyPrice() {
        return $this->getOwner()->getOwner()->price ;
    }

    public function getMyDesc() {
        return Yii::t('Shop','goods_desc_'.$this->getOwner()->getOwner()->goodsId);
    }

}
