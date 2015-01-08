<?php

class ShopModel extends Model{
    
    private $playerId ;
    private $goods ;
    private $money ;
    private $player ;
        
    public function __construct($playerId){
        $this->playerId = $playerId ;
        $this->money  = new PlayerMoney($playerId);
        $this->player = Yii::app()->objectLoader->load('Player',$this->playerId);
    }

    public function getByCategory($category){
        if( empty($this->goods[$category]) ) {
            $this->goods[$category] = ShopGoods::getCategoryGoods($category);
        }
        return $this->goods[$category];
    }
    
    public function buyGoods($goodsId,$number=1){
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $goods = Yii::app()->objectLoader->load('ShopGoods',$goodsId);
        if( empty($goods) ) {
            throw new SException( Yii::t('Shop','you could not buy the goods') );
        }

        $this->checkGoods($goodsId);

        if( $goods->moneyType==1 ) {
            //扣除需要消耗的金钱
            $playerMoney = Yii::app()->objectLoader->load( 'PlayerMoney',$this->playerId );
            //扣除需要消耗的金钱
            $playerMoney->reduce( $goods->getPrice()*$number,'buy goods:'.$goodsId.'*'.$number,$goodsId ) ;
        }
        else {
            //扣除需要消耗的游戏币
            $player->subGold( $goods->getPrice()*$number ) ;
        }
        $result = $goods->setEffect(Yii::app()->objectLoader->load('Player',$this->playerId),$number);
        return $result ;
    }
    
    
    public function sendGoods($sendPlayerId,$goodsId,$number=1){
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $goods = Yii::app()->objectLoader->load('ShopGoods',$goodsId);
        $sendPlayer = Yii::app()->objectLoader->load('Player',$sendPlayerId);
        if( empty($goods) ) {
            throw new CException( 'you could not buy the goods' );
        }
        if( $goods->category!=2 ) {
            throw new CException('the goods could not send');
        }
        if( PlayerFriend::isFriend($this->playerId,$sendPlayerId)!=0 ) {
            throw new SException(Yii::t('Friend', 'you are not friends'));
        }

        $this->checkGoods($goodsId);
        $transaction = Yii::app()->db->beginTransaction();

        try{
            if( $goods->moneyType==1 ) {
                $playerMoney = Yii::app()->objectLoader->load( 'PlayerMoney',$this->playerId );
                //扣除需要消耗的金钱
                $playerMoney->reduce( $goods->getPrice()*$number,'send to ['.$sendPlayerId.'] and buy goods:'.$goodsId.'*'.$number,$goodsId ) ;
            }
            else {
                //扣除需要消耗的金钱
                $player->subGold( $goods->getPrice()*$number ) ;
            }
            $result = $goods->sendEffect($player,$sendPlayer,$number);
            $transaction->commit();
            return $result ;
        } catch (Exception $e) {
            $transaction->rollBack();  
            throw $e ;
        } 
    }
    
    public function checkGoods($goodsId,$isException=true){
        try{
            $goods = Yii::app()->objectLoader->load('ShopGoods',$goodsId);
            if( empty($goods) ) {
                throw new SException( Yii::t('Shop','you could not buy the goods') );
            }
            $goods->checkGoods(Yii::app()->objectLoader->load('Player',$this->playerId));
            return true ;
        } catch ( Exception $e ) {
            if( $isException ) {
                throw $e ;
            }
            else {
                return false ;
            }
        }
    }
    
    
    public function getPlayerMoney(){
        return $this->money->getMoney() ;
    }
    
    public function checkShopEnable() {
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        return true;
    }

    public static function getBreedCDGoods() {
        return Yii::app()->objectLoader->load('ShopGoods',80001);
    }

}

