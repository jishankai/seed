<?php

class CatalogController extends Controller{

    public function init() {
        parent::init();
        $this->layout = "//layouts/theme";
    }

    public function actionIndex(){
        Yii::app()->objectLoader->load('SeedModel',$this->playerId)->checkSeedGrowState();
        $catalog = Yii::app()->objectLoader->load('Catalog',$this->playerId);
        $params = array(
            'bodyList'  => SeedData::getBodyData() ,
            'catalog'       => $catalog ,
        );
        if( $this->actionType==REQUEST_TYPE_AJAX ) {
            $this->display( $this->renderPartial('index',$params,true) );
        }
        else {
            $this->display( 'index',$params );
        }
    }

    public function actionSubMain($bodyId){
        Yii::app()->objectLoader->load('SeedModel',$this->playerId)->checkSeedGrowState();
        $catalog = Yii::app()->objectLoader->load('Catalog',$this->playerId);
        //$catalog->addToList( new Seed(664) );
        //self::dump( $catalog->getContentData() );
        //self::dump( $catalog->getCount(101001) );
        //self::dump( $catalog->getCount(101001,true) );

        $max = $catalog->getBodyCountMax($bodyId);
        $params = array(
            'catalogList'   => CatalogData::getAll() ,
            'catalog'       => $catalog ,
            'bodyId'        => $bodyId ,
            'count'         => $catalog->getCountSum($bodyId) ,
            'max'           => $catalog->getMaxCount($bodyId) ,
        );

        if( $this->actionType==REQUEST_TYPE_AJAX ) {
            $this->display( $this->renderPartial('subMain',$params,true) );
        }
        else {
            $this->display( 'subMain',$params );
        }
    }

    public function actionSubIndex($bodyId,$budId) {
        $catalogType = isset($_REQUEST['type'])?intval($_REQUEST['type']):1;
        Yii::app()->objectLoader->load('SeedModel',$this->playerId)->checkSeedGrowState();
        if( $catalogType>2||$catalogType<1 ) $catalogType = 1 ;
        $catalogId = $catalogType*100000+$bodyId ;
        $catalog = Yii::app()->objectLoader->load('Catalog',$this->playerId);
        $params = array(
            'catalog'       => $catalog ,
            'bodyId'        => $bodyId ,
            'budId'         => $budId ,
            'catalogId'     => $catalogId ,
            'catalogType'   => $catalogType ,
            'catalogData'   => Yii::app()->objectLoader->load('CatalogData',$catalogId),
            'playerData'    => $catalog->getPlayerData($catalogId)
        );

        if( $this->actionType==REQUEST_TYPE_AJAX ) {
            $this->display( $this->renderPartial('subIndex',$params,true) );
        }
        else {
            $this->display( 'subIndex',$params );
        }
    }

    public function actionBuy( $bodyId,$faceId,$budId,$gardenId ) {
        try {
            $transaction = Yii::app()->db->beginTransaction() ;
            $catalog = Yii::app()->objectLoader->load('Catalog',$this->playerId);
            $catalog -> buySeed( $bodyId,$faceId,$budId,$gardenId ) ;
            $transaction->commit();
        } catch ( Exception $e ) {
            $transaction->rollBack();
            throw $e ;
        }
        $this->display( Yii::t('Catalog','buy seed success') );
    }

    public function actionFeedDialog() {
        $page = isset($_REQUEST['page'])?intval($_REQUEST['page']):1;
        $catalog = Yii::app()->objectLoader->load('Catalog',$this->playerId);
        $dataList = $catalog->getDressAll() ;
        $pageSize = 4 ;
        $countMax = count( $dataList ) ;
        $maxPage = max(1,ceil( $countMax/$pageSize ));
        $params = array(
            'dataList'  => $dataList ,
            'maxPage'   => $maxPage ,
            'pageSize'  => $pageSize ,
            'page'      => $page ,
            'currentCount'=> count($dataList) ,
            'maxCount'  => $catalog->getDressMaxCount() ,
        ) ;
        $this->display( $this->renderPartial('feedDialog',$params,true) );
        //$this->display( 'feedDialog',$params );
    }

}