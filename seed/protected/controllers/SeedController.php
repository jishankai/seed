<?php

class SeedController extends Controller{
    public $layout = "//layouts/theme";
    
    public function actionFavourite() {
        if( empty($_REQUEST['seedId']) ) {
            throw new CException( 'seed error' );
        }
        $seedId = intval($_REQUEST['seedId']);
        try{
            $transaction = Yii::app()->db->beginTransaction() ;
            $seedModel = Yii::app()->objectLoader->load('SeedModel',$this->playerId);
            $seedModel -> setFavouriteSeed( $seedId );
            $transaction -> commit();
            $this->display();
        } catch (Exception $e) {
            $transaction -> rollBack();
            throw $e ;
        }
        
    }

    public function actionSell( $seedId ){
        $seed = Yii::app()->objectLoader->load('Seed',$seedId) ;
            $seed->checkExists();
        if( empty($seed->bodyId) ) {
            throw new CException( 'seed error' );
        }
        $seedModel = Yii::app()->objectLoader->load('SeedModel',$this->playerId);
        $result = $seedModel->sellSeed($seedId);
        $this->display( 
            array(
                'price' => $result['getGold'],
                'exp'   => $result['getExp'],
            )
        );
    }
    
    

    public function actionBreedApply( $fromSeedId,$partnerSeedId ) {
        $clearCDTime = empty($_REQUEST['clearCDTime'])?0:1;
        //若与好友繁殖，好友种子为from
        if( empty($fromSeedId)||empty($partnerSeedId) ) {
            throw new SException( Yii::t('Seed','select the seed error') );
        }
        $isFriend = false ;
        try{
            $transaction = Yii::app()->db->beginTransaction() ;
            $seedModel = Yii::app()->objectLoader->load('SeedModel',$this->playerId);
            $partnerSeed = Yii::app()->objectLoader->load('Seed',$partnerSeedId);

            //是否为虚拟好友
            $id = VisualFriend::checkOwnerBySeed($fromSeedId);
            if ($id) {
                //只有第一个虚拟好友可以用来繁殖
                //if ($id=1001) {
                    $key = VisualFriend::createKey($id, $this->playerId);
                    $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
                    $fromSeed = $visualFriend->getSeed($fromSeedId);
                    $result = $seedModel->breedSeeds( $fromSeed,$partnerSeed,false,$clearCDTime );
                    $visualFriend->saveBreedCDTime($fromSeedId) ;
                    $isFriend = true ;
                //}
            } else {
                $fromSeed = Yii::app()->objectLoader->load('Seed',$fromSeedId);
                $result = $seedModel->breedSeeds( $fromSeed,$partnerSeed,true,$clearCDTime );
                $guideModel = Yii::app()->objectLoader->load('GuideModel',$this->playerId);
                if( $guideModel->isCurrentGuide(80) ){
                    $guideModel->saveStatus(85);
                }
                if( $this->playerId!=$fromSeed->playerId ) {
                    $fromSeed->saveBreedCDTime() ;
                    $isFriend = true ;
                }
            }
            $transaction -> commit() ; 
        } catch ( Exception $e ) {
            $transaction->rollBack(); 
            throw $e ;
        }
        $params = array(
            'spaceSize' => Yii::app()->objectLoader->load('GardenModel',$this->playerId)->surplusPosition(),
            'isNative'  => !empty($_REQUEST['native']) ,
            'isFriend'  => $isFriend ,
        );
        
        $this->display( $this->renderPartial('breedResult',array_merge($params,$result),true) );
        
        
    }
    

    public function actionBreedIndex() {
        $seedId = isset($_REQUEST['seedId'])?intval($_REQUEST['seedId']):0 ;
        $gardenId = isset($_REQUEST['selectId'])?intval($_REQUEST['selectId']):0 ;
        if( $vid = VisualFriend::checkOwnerBySeed($seedId) ){
            $visualFriend = Yii::app()->objectLoader->load('VisualFriend',VisualFriend::createKey($vid,$this->playerId));
            $seed = $visualFriend->getSeed($seedId);
            $isVisual = true ;
        }
        else {
            $seed = Yii::app()->objectLoader->load('Seed',$seedId) ;
            $isVisual = false ;
        }
        if( empty($gardenId) ) $gardenId = $seed->gardenId ;
        $seedModel = Yii::app()->objectLoader->load('SeedModel',$this->playerId);
        $seedList = $seedModel->getGardenSeeds( $gardenId );
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $params = array(
            'seedId'    => $seedId ,
            'seed'      => $seed ,
            'seedList'  => $seedList,
            'gardenId'  => $gardenId ,
            'supplyPower'   => $player->getPlayerPoint('supplyPower')->getRemainTime() ,
            'isNative'      => !empty($_REQUEST['native']) ,
            'breedCDTime'   => $seed->getBreedCDTime() ,
            'isCurrentGuide'=> Yii::app()->objectLoader->load('GuideModel',$this->playerId)->isCurrentGuide(80),
        );
        $data = $this->display('breedIndex', $params, true);
        //$this->display( $this->renderPartial('breedIndex', $params, true) );
    }


    
    public function actionDetail( $seedId ){
        if( empty($seedId) ) {
            $seed = null ;
        }
        else {
            if( $vid = VisualFriend::checkOwnerBySeed($seedId) ){
                $visualFriend = Yii::app()->objectLoader->load('VisualFriend',VisualFriend::createKey($vid,$this->playerId));
                $seed = $visualFriend->getSeed($seedId);
                $isVisual = true ;

            }
            else {
                $seed = Yii::app()->objectLoader->load('Seed',$seedId) ;
                $isVisual = false ;
            }
            if( $vid = VisualFriend::checkOwnerByGarden($seed->gardenId) ) {
                $isVisualGarden = true ;
                $supplyPower = VisualFriend::getSupplyPowerRemainTime();
                $gardenPlayerId = $vid;
                $garden = VisualFriend::getGarden($seed->gardenId);
            }
            else {
                $isVisualGarden = false ;
                $garden = Yii::app()->objectLoader->load('Garden',$seed->gardenId);
                $gardenPlayer = Yii::app()->objectLoader->load('Player',$garden->playerId);
                $supplyPower = $gardenPlayer->getPlayerPoint('supplyPower')->getRemainTime();
                $gardenPlayerId = $garden->playerId;
            }
        }
        if( !$seed->isExists()||empty($seed->gardenId) ) {
            $this->showGlobalMessage = false ;
            GlobalState::set($this->playerId,'REFRESH_SEED',$seedId);
            $this->error( Yii::t('Seed','seed not exists') );
        }
        else {
            $seedModel = Yii::app()->objectLoader->load('SeedModel',$this->playerId);
            $player = Yii::app()->objectLoader->load('Player',$this->playerId);
            $params = array(
                'seedId'    => $seedId ,
                'seed'      => $seed ,
                'garden'    => $garden ,
                'supplyPower'   => $supplyPower,
                'gardenCount'   => $player->gardenNum ,
                'isAjax'        => $this->actionType===REQUEST_TYPE_AJAX ,
                'isFriend'      => $isVisual||PlayerFriend::isFriend($seed->playerId, $this->playerId)==0?1:0 ,
                'breedLock'     => $seed->checkLevel($player->level,true)?0:1 ,
                'isCurrentGuide'=> Yii::app()->objectLoader->load('GuideModel',$this->playerId)->isCurrentGuide(80),
                'isVisual' => $isVisual,
                'gardenPlayerId'=> $gardenPlayerId ,
            );
            $viewFile = $seed->isOwner($this->playerId)&&$seed->isFoster!=1?'viewMine':'viewOther';
            
            if( $this->actionType===REQUEST_TYPE_AJAX ) {
                $this->display( $this->renderPartial($viewFile, $params, true) );
            }
            else {
                $this->display($viewFile, $params);
            }
        }
    }

    public function actionGrowData( $seedId,$growPeriod ) {
        $seed=Yii::app()->objectLoader->load('Seed',intval($seedId));
        $seed->checkExists();
        $data = $seed->getGrowValueData( $growPeriod );
        $data['url'] = $this->createUrl('seed/growData',array('seedId'=>$seedId,'growPeriod'=>$data['growPeriod']));
        $this->display( $data );
    }


    public function actionFeedIndex($seedId) {
        $dressId = isset($_REQUEST['dressId'])?intval($_REQUEST['dressId']):0 ;
        $seed = Yii::app()->objectLoader->load('Seed',$seedId) ;
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $itemModel = Yii::app()->objectLoader->load('ItemModel', $this->playerId);
        $blankFillItems = array();
        if( $seed->isGrown() ) {
            $itemList = $itemModel->getCItemInfo('resItem');
            $blankFill = 0 ;
        }
        else {
            $itemList = $itemModel->getGrowItemInfo() ;
            $blankFill = count($itemList)>0?1:0;
            //var_dump($itemList); exit ;
            foreach( array(31,32,33) as $itemId ) {
                if( isset($itemList[$itemId]) ) continue ;
                $itemMeta = Yii::app()->objectLoader->load('ItemMeta',$itemId) ;
                $itemList[$itemId] = array(
                    'itemMeta'  => $itemMeta ,
                    'item'      => $itemMeta->itemObject ,
                    'pile'  => 0 ,
                    'num'   => 0 ,
                );
            }
        }
        $params = array(
            'seedId'    => $seedId ,
            'seed'      => $seed ,
            'itemList'  => $itemList ,
            'isNative'  => !empty($_REQUEST['native']) ,
            'supplyPower'   => $player->getPlayerPoint('supplyPower')->getRemainTime() ,
            'blankFill'     => $blankFill ,
            'dressId'       => $dressId ,
            'dressData'     => empty($dressId)?false:Yii::app()->objectLoader->load('SeedDressData',$dressId),
            'isCurrentGuide'=> Yii::app()->objectLoader->load('GuideModel',$this->playerId)->isCurrentGuide(40),
        );
        //$this->display($this->renderPartial('feedIndex', $params, true));
        $this->display('feedIndex',$params);
    }





    public function actionFeedApply($seedId){
        $items = isset($_REQUEST['items'])?$_REQUEST['items']:array();
        if( empty($seedId)||empty($items)||!is_array($items)  ) {
            throw new CException( 'data error' );
        }
        $seed = Yii::app()->objectLoader->load('Seed',$seedId) ;
        $seed->checkExists() ; 
        $seed->checkOwner( $this->playerId ) ;
        $preGrowPeriod = $seed->growPeriod ;
        $isGrown = $seed->isGrown() ;

        $itemCount = array_sum( $items );

        try{
            $transaction = Yii::app()->db->beginTransaction() ;
            $itemModel = Yii::app()->objectLoader->load('ItemModel',$this->playerId);

            if( $isGrown ) {
                if( $itemCount>$seed->feedCount ) {
                    throw new CException('feed count not enough');
                }
                $seed->feedCount -= $itemCount ;
                $seed->saveAttributes( array('feedCount') );
            }

            $log = "" ;
            foreach( $items as $itemId=>$num ) {
                if($itemId>0) {
                    for($i=0;$i<$num;$i++) {
                        $itemMeta = Yii::app()->objectLoader->load('ItemMeta',$itemId);
                        if( $isGrown&&$itemMeta->itemObject->category!=1 ) {
                            throw new SException( Yii::t('Seed','Error item for seed') );
                        }
                        $itemModel->itemUse( $itemId,$seedId );
                    }
                }
                else {
                    //do nothing 
                }
                $log .= $itemId.'*'.$num.'; ';
            }
            $seed->saveLog('feed','items:'.$log);
            GlobalState::set($this->playerId,'REFRESH_SEED',$seedId);
            //检查是否可以生成装备
            $seed->checkGrowDress() ;

            if( $preGrowPeriod != $seed->growPeriod ) {
                GlobalState::set($this->playerId,'REFRESH_SEED',$seedId);
            }
            if ($itemCount>0) {
                //喂食成就检查
                $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_SEEDFEED, array('value'=>$itemCount));
                $achieveEvent->onAchieveComplete();
            }
            $guideModel = Yii::app()->objectLoader->load('GuideModel',$this->playerId);
            if( $guideModel->isCurrentGuide(40) ){
                $guideModel->saveStatus(49);
                GuideModel::checkGuideState( $this->playerId );
            }

            $transaction -> commit();

            $result = array(
                'feedCount' => $seed->feedCount ,
                'growData'  => $seed->getGrowValueData(),
                'animationData' => $seed->getAnimationData(),
                'growSeconds'   => $seed->getGrowLimitSecond() ,
                'attributes'    => $seed->getFeedAttributes(),
                'supplyPowerTime' => intval(Yii::app()->objectLoader->load('Player',$seed->playerId)->getPlayerPoint('supplyPower')->getRemainTime($seed->getLastGrowTime())) ,
                'seedName'  => $seed->getName(),
                'isEquiped' => empty($seed->dressId)?0:1,
            );
            $this->display( $result );
        } catch (Exception $e) {
            $transaction -> rollBack();
            throw $e ;
        }
    }


    public function actionGetDisplayInfo( $seedIds=0,$gardenId=0 ) {
        if( !empty($seedIds) ) {
            if( !is_array($seedIds) ) {
                $seedIds = explode( ",", $seedIds );
            }
            $seeds = array();
            foreach( $seedIds as $seedId ) {
                $seeds[$seedId] = Yii::app()->objectLoader->load('Seed',$seedId);
            }
        }
        elseif( !empty($gardenId) ){
            $seedModel = Yii::app()->objectLoader->load('SeedModel',$this->playerId);
            $seeds = $seedModel->getGardenSeeds( $gardenId,true );
        }
        else {
            throw new CException('Error');
        }
        $result = array() ;
        foreach( $seeds as $seed ) {
            if( !$seed->isExists() ) {
                $array = array(
                    'seedId'    => $seed->seedId ,
                    'isExists'  => 0 ,
                );
            }
            else {
                $array = $seed->getNativeData() ;
                $array['isExists'] = 1 ;
            }
            $result[$seed->seedId] = $array ;
        }
        $this->display($result);
    }

    public function actionFoster() {
        if( empty($_REQUEST['seedId'])||empty($_REQUEST['gardenId']) ) {
            throw new SException('input error');
        }
        $seedId = intval($_REQUEST['seedId']) ;
        $gardenId = intval($_REQUEST['gardenId']) ;
        try{
            $transaction = Yii::app()->db->beginTransaction() ;
            $id = VisualFriend::checkOwnerByGarden($gardenId);

            //虚拟好友寄养
            if ($id) {
                $key = VisualFriend::createKey($id, $this->playerId);
                $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
                $visualFriend->fosterSeed($seedId, $gardenId);
            } else {
                $garden = Yii::app()->objectLoader->load('Garden',$gardenId) ;
                $fosterModel = Yii::app()->objectLoader->load('FosterModel',$this->playerId) ;
                $fosterModel->fosterSeed( $seedId,$garden->playerId,$garden->gardenSign );
            }
            GlobalState::set($this->playerId,'NATIVE_CLOSE','close');
            $transaction->commit();
            $this->display( Yii::t('Seed','foster success') );
        } catch ( Exception $e ) {
            $transaction->rollBack();
            throw $e ;
        }
    }




}
