<?php 

class ShopGoodsItem extends CBehavior {

    public function parseEffect( $player,$number=1 ) {
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta',$this->getOwner()->getOwner()->internalId);
        $playerItem = Yii::app()->objectLoader->load('Item',$player->playerId);
        $playerItem -> addItem( $itemMeta,'ShopGoods',$number );

        //任务检查
        $missionEvent = new MissionEvent($player->playerId, MISSIONEVENT_DECOBUY, array('itemId'=>$this->getOwner()->getOwner()->internalId));
        $missionEvent->onMissionComplete();
    }

    public function parseSendEffect( $fromPlayer,$toPlayer,$number=1 ) {
        $mailInfo = array(
            'informType'=> 2 ,
            'fromId'    => $fromPlayer->playerId,
            'goodsId'   => $this->getOwner()->getOwner()->internalId ,
        );
        MailModel::givePresent( $mailInfo, array($toPlayer->playerId) );
    }

    public function getMyName() {
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta',$this->getOwner()->getOwner()->internalId);
        return $itemMeta->getName() ;
    }

    public function getMyPrice() {
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta',$this->getOwner()->getOwner()->internalId);
        return $itemMeta->itemObject->price ;
    }

    public function getMyDesc() {
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta',$this->getOwner()->getOwner()->internalId);
        return $itemMeta->getDescription() ;
    }

    public function getMyImage() {
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta',$this->getOwner()->getOwner()->internalId);
        return $itemMeta->getImagePath() ;
    }

}
