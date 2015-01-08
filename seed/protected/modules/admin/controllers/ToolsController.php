<?php

class ToolsController extends Controller {
    public $playerId = 10002 ;

    public function actionIndex(){
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $params = array(
            'nextLevelExp'  => $player->nextLevelExp() ,
            'currentExp'    => $player->exp ,
            'player'        => $player ,
        );
        $this->render('view', $params);
    }


    public function actionFeed(){
        $this->showGlobalMessage = false ;
        $seedId = isset($_REQUEST['seedId'])?intval($_REQUEST['seedId']):0 ;
        $seed = Yii::app()->objectLoader->load('Seed',$seedId) ;
        if( empty($seed->bodyId) ) {
            throw new SException( 'seed error' );
        }

        $addAttributes = isset($_REQUEST['attributes'])?$_REQUEST['attributes']:array();
        $addGrowValue  = isset($_REQUEST['growValue'])?$_REQUEST['growValue']:array();
        $msg = "";
        try{
            $transaction = Yii::app()->db->beginTransaction() ;
            if( !empty($_REQUEST['rand']) ) {
                if( rand(1,2)==1||$seed->isGrown() ){
                    $addGrowValue = $addValue = rand(1,20);
                    $addAttributes = array() ;
                    $msg = "你幸运的捡到一坨便便，种子飞速的成长中...\n";
                }
                else {
                    $addGrowValue = 0 ;
                    $addAttributes = array(1=>rand(1,10),2=>rand(1,10),3=>rand(1,10),4=>rand(1,10),5=>rand(1,10),6=>rand(1,10) ); 
                    $msg = "你栽了个跟头，种子开心的笑了，属性长了不少...\n";
                }

            }
            if( !empty($addAttributes) ) {
                if( $seed->feedCount<1 ) {
                    throw new SException('喂食机会用完了，还是把我卖了吧~~!');
                }
                else {
                    $seed->feedCount -= 1 ;
                    $seed->saveAttributes( array('feedCount') );
                }
                $seed -> addFeedAttributes( $addAttributes  );
                $transaction -> commit();
                $this->display($msg."增加属性：".print_r($addAttributes,true)."最新属性  ".print_r($seed->getFeedAttributes(),true) );
            }
            elseif( !empty($addGrowValue) ) {  
                if( $seed->isGrown() ) { $msg="你想撑死我哟~~\n"; }
                    $seed->setGrowValue( $addGrowValue );
                $transaction -> commit();
                $this->display($msg."成长值增加 $addGrowValue  最新成长值：  ".$seed->getGrowValue() );
            }
            else {
                $transaction -> commit();
                $this->display( "别太抠门了，多少给点啦！" );
            }

        } catch (Exception $e) {
            $transaction -> rollBack();
            throw $e ;
        }

    }

    public function actionGenerate() {
        $bodyId = isset($_REQUEST['bodyId'])?intval($_REQUEST['bodyId']):0 ;
        $faceId = isset($_REQUEST['faceId'])?intval($_REQUEST['faceId']):0 ;
        $budId = isset($_REQUEST['budId'])?intval($_REQUEST['budId']):0 ;
        $dressId = isset($_REQUEST['dressId'])?intval($_REQUEST['dressId']):0 ;
        $playerId = isset($_REQUEST['playerId'])?intval($_REQUEST['playerId']):$this->playerId ;
        $seedModel = Yii::app()->objectLoader->load('SeedModel',$playerId) ;


        if( !empty($_REQUEST['rand']) ) {
            $newSeed = $seedModel -> generateSeed();
            $gardenModel = Yii::app()->objectLoader->load('GardenModel',$playerId);
            $gardenModel -> addSeedToGarden( $newSeed->seedId );
            $message = "你是个幸运的孩子，上天待你不薄！ ";
        }
        else{
            $newSeed = $seedModel -> addSeed($bodyId,$faceId,$budId,1,SEED_FROM_ADMIN);
            if( !empty($dressId) ) {
                $newSeed->setGrowValue( $newSeed->getMaxGrowValue() );
                $newSeed->setDressId( $dressId );
            }


            $message = "哇哇!! 学习会自己造种子了！ ";
        }

        $this->display($message."新的种子\n名字：[".$newSeed->getName()."] 它的ID：".$newSeed->seedId );
    }

    public function actionShow(){
        $this->display('showSeeds') ;
    }

    public function actionGetItem( $itemId,$number,$playerId ) {
        $player = Yii::app()->objectLoader->load('Player',$playerId);
        $item = Yii::app()->objectLoader->load('Item',$playerId);
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta',$itemId);
        $number = intval($number) ;
        $item->addItem( $itemMeta,'system send',$number );
        $this->display("玩家 “".$player->playerName."”获得了（".$number."）个【".$itemMeta->getName()."】");
    }

    public function actionAddExp( $value ) {
        $player = $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $value = max(1,intval($value)) ;
        $player->addExp($value);
        $this->display();
    }



}