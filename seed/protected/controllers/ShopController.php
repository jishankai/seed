<?php
class ShopController extends Controller {

    public function actionIndex() {
        $this->layout = "//layouts/theme";
        $category = isset($_GET['category'])?intval($_GET['category']):2;
        $shopModel = Yii::app()->objectLoader->load('ShopModel',$this->playerId);

        $categories = array(
            1 => ' ' ,
            2 => ' ' ,
            6 => ' ' ,
            4 => ' ' ,
        );

        if( !isset($categories[$category]) )$category = 2 ;
        $goodsList = $shopModel->getByCategory( $category );
        
        //self::dump($goods);
        $params = array(
            'categories'=> $categories ,
            'goodsList' => $goodsList ,
            'category'  => $category ,
        );
        if( $this->actionType==REQUEST_TYPE_AJAX ) {
            $this->display( $this->renderPartial('goodsList',$params,true) );
        }
        else {
            $this->display( 'goodsList',$params );
        }
    }


    public function actionBuy() {
        $goodsId = isset($_GET['goodsId'])?intval($_GET['goodsId']):0 ;
        $shopGoods = Yii::app()->objectLoader->load('ShopGoods',$goodsId);
        $result = array( 
            'type'      => 1 ,
            'message'   => $this->renderPartial('goodsConfirm',array('goods'=>$shopGoods),true),
            'goodsId'   => $goodsId ,
        ) ;
        $this->display( $result );
        
    }

    public function actionConfirm() {
        $goodsId = isset($_GET['goodsId'])?intval($_GET['goodsId']):0 ;
        $number = isset($_GET['num'])?intval($_GET['num']):1 ;
        $shopModel = Yii::app()->objectLoader->load('ShopModel',$this->playerId);

        try{
            $transaction = Yii::app()->db->beginTransaction();
            $shopModel->buyGoods( $goodsId,$number ) ;
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();  
            throw $e ;
        } 
        $this->display( Yii::t('Shop','buy success') );
    }



    public function actionSend() {
        $goodsId = isset($_GET['goodsId'])?intval($_GET['goodsId']):0 ;
        $shopGoods = Yii::app()->objectLoader->load('ShopGoods',$goodsId);
        if( $shopGoods->category!=2 ) {
            throw new CException('the goods could not send');
        }
        $result = array( 
            'type'      => 1 ,
            'message'   => $this->renderPartial('sendConfirm',array('goods'=>$shopGoods),true),
            'goodsId'   => $goodsId ,
        ) ;
        $this->display( $result );
        
    }

    public function actionSendConfirm() {
        $goodsId = isset($_GET['goodsId'])?intval($_GET['goodsId']):0 ;
        $number = isset($_GET['num'])?intval($_GET['num']):1 ;
        $sendPlayerId = isset($_GET['sendPlayerId'])?intval($_GET['sendPlayerId']):0 ;
        $shopModel = Yii::app()->objectLoader->load('ShopModel',$this->playerId);
        $shopModel->sendGoods( $sendPlayerId,$goodsId,$number ) ;
        $this->display( Yii::t('Message','shop send success') );
    }


}   

