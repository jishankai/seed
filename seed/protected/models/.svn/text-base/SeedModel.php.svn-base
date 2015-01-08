<?php

/**
 * 种子相关的操作Model
 * 
 */
class SeedModel extends Model{
    public $playerId ;
	
    public function __construct($playerId) {
        $this->playerId = $playerId ;
    }
    
    /**
     * 获得一个玩家的所有种子信息
     * 
     * return array 
     */
    public function getPlayerSeeds(){
        return Seed::multiLoad( array('playerId'=>$this->playerId,'isSell'  => 0 ,),false ) ;
    }
    
    /**
     * 获得一个花园的所有种子
     * 
     * @param $gardenId  花园id
     * @param $includeFoster 是否包含别人寄养的种子
     * @return array 
     */
    public function getGardenSeeds( $gardenId,$includeFoster=false ) {
        $garden = Yii::app()->objectLoader->load('Garden',$gardenId);
        $seedIds = empty($garden->seedList)?array():explode(',',$garden->seedList);
        if( $includeFoster&&!empty($garden->fosterList) ) {
            $seedIds[] = $garden->fosterList ;
        }

        return self::getSeedsByIds( $seedIds );
        /*
        $params = array( 
            'gardenId'  => $gardenId ,
            'isSell'    => 0 ,
        ) ;
        if( !$includeFoster ) {
            $params['playerId'] = $this->playerId ;
            $params['isFoster'] = 0 ;
        }
        return Seed::multiLoad( $params,false ) ;
        */
    }
    
    /**
     * 根据一个种子ID组成的数组获得一堆种子对象
     * 
     * @param array $seedIds
     */
    public static function getSeedsByIds( $seedIds ){
        if( empty($seedIds) ) {
            return array();
        }
        return Seed::multiLoad( array('seedIds'=>$seedIds),false );
    }
    
    /**
     * 更新种子的成长值 
     * 
     * @param $gardenId
     * @return void 
     */
    public function updateSeedGrowValue( $gardenId=0,$isForce=false ){
        static $lastUpdateTime = 0 ;
        if( !$isForce && $lastUpdateTime==time() ) {
            return ;
        }
        $lastUpdateTime = time();
        if( empty($gardenId) ) {
            $gardens = Garden::multiLoad( array('playerId='.intval($this->playerId) ) );
        }
        else {
            $gardens = array( $gardenId=>$gardenId );
        }
        foreach( $gardens as $gid=>$g ) {
            $seeds = $this->getGardenSeeds($gid,true) ;
            foreach( $seeds as $seed ) {
                if( $seed->state<0||$seed->growPeriod>=SEED_GROW_PERIOD_GROWN ) {
                    continue ;
                }
                else {
                    $seed -> setGrowValue( 0,time() );
                }
            }
        }
        return true ;
    }

    public function checkSeedGrowState() {
        $session = Yii::app()->session ;
        $sessionKey = 'seedLastRefreshTime' ;
        $currentTime = time();
        if( !isset( $session[$sessionKey] )||$currentTime-$session[$sessionKey]>30 ) {
            foreach( $this->getPlayerSeeds() as $seed ) {
                $seed->getGrowValue();
            }
        }
    }
    
    /**
     * 按照条件添加一个种子 并返回新的种子
     * 
     * @param int $bodyId
     * @param int $faceId
     * @param int $budId
     * @param int $gardenId
     * @return Seed Object
     */
    public function addSeed( $bodyId,$faceId,$budId,$gardenIndex=1,$from=SEED_FROM_SYSTEM,$params=array() ){
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);

        $bodyData = Yii::app()->objectLoader->load('SeedData',$bodyId);
        $faceData = Yii::app()->objectLoader->load('SeedData',$faceId);
        $budData  = Yii::app()->objectLoader->load('SeedData',$budId);
        
        $bodyData->checkLevel($player->level);
        $faceData->checkLevel($player->level);
        $budData ->checkLevel($player->level) ;

        $createInfo = array(
            'playerId'  => $this->playerId ,
            'bodyId'    => $bodyId ,
            'faceId'    => $faceId ,
            'budId'     => $budId ,
            'gardenId'  => 0 ,
            'feedCount' => 5 ,
            'maxGrowValue'  => $bodyData->growValue+$faceData->growValue+$budData->growValue ,
            'getFrom'   => $from ,
            'attributes'=> '',
            'hideAttribute' => rand(0,99) ,
        );
        if( !empty($params) ) {
            $createInfo = array_merge( $createInfo,$params );
        }
        $newSeedId = Seed::create($createInfo);
        $newSeed = Yii::app()->objectLoader->load('Seed',$newSeedId) ;
        //Yii::app()->objectLoader->load('Catalog',$this->playerId)->addToList( $newSeed );
        if( !empty($gardenIndex) ) {
            Yii::app()->objectLoader->load('GardenModel',$this->playerId)->addSeedToGarden( $newSeedId,$gardenIndex );
        }

        return $newSeed ;
    
    }
    
    /**
     * 种子繁殖
     * 
     * @param Seed Object $seed
     * @param Seed Object $partnerSeed
     */
    public function breedSeeds( $fromSeed,$partnerSeed,$checkFriend=true,$clearCDTime=false ){
        $result = array();
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        
        //检查花园是否有足够的空间 
        $gardenModel = Yii::app()->objectLoader->load('GardenModel',$this->playerId);
        
        if( $gardenModel->surplusPosition()<1 ) {
            throw new SException( Yii::t('Seed','the first garden has not enough space') ) ;
        }
        
        //检查两个种子之一 是否是当前用户所有的
        if( !$fromSeed->isOwner($this->playerId)&&!$partnerSeed->isOwner($this->playerId) ){
            throw new SException( Yii::t('Seed','select the seed error') );
        }
        $result['spendMoney'] = 0 ;
        //检查两个种子所有者是否是好友  确定是否可以相互繁殖
        if( $fromSeed->playerId!=$partnerSeed->playerId ) {
            if( $checkFriend && PlayerFriend::isFriend($fromSeed->playerId, $partnerSeed->playerId)!=0 ) {
                throw new CException('Not friend\'s seeds.');
            }
            //检查第二个种子是不是用户自己所有
            if( $partnerSeed->playerId!=$this->playerId ) {
                throw new CException('the seeds can not breed');
            }
            if( $fromSeed->getBreedCDTime()>0 ) {
                if( $clearCDTime ) {
                    $shopModel = Yii::app()->objectLoader->load('ShopModel',$this->playerId);
                    $shopModel->buyGoods( 80001 );
                    $fromSeed->saveBreedCDTime();
                    //消耗的金钱
                    $result['spendMoney'] = ShopModel::getBreedCDGoods()->price ;
                }
                else {
                    throw new SException('seed not has breedTime',EXCEPTION_TYPE_BREED_CDTIME);
                }
            }
        }
        else {
            //两个种子是否在同一个花园,是否都已经成熟
            if( ($fromSeed->isFoster==0&&$fromSeed->gardenId!=$partnerSeed->gardenId)||!$fromSeed->isGrown()||!$partnerSeed->isGrown() ) {
                throw new CException( 'the seeds can not breed' );
            }
        }


        
        //扣除需要消耗的金钱
        $result['spendGold'] = self::getBreedNeedMoney($fromSeed,$partnerSeed) ;
        $player->subGold( $result['spendGold'] ) ;
        
        //消耗行动力
        $result['spendActionPoint'] = 2 ;
        $player->getPlayerPoint('actionPoint')->subValue($result['spendActionPoint']) ;

        //可以获得的经验
        $result['getExp'] = floor($player->level/10)+5 ;
        $player->addExp( $result['getExp'] ); 

        $maxRate = ($fromSeed->bodyId==$partnerSeed->bodyId?1:2)*($fromSeed->faceId==$partnerSeed->faceId?1:2)*($fromSeed->budId==$partnerSeed->budId?1:2)+1 ;
        if( $fromSeed->hideAttribute+$partnerSeed->hideAttribute>=95 && $fromSeed->hideAttribute+$partnerSeed->hideAttribute<=105 && rand(1,$maxRate)==1 ) {
            //变异种子
            $newSeed = $this->generateBreedChangedSeed();
            $variationMessage = 'variation!';
        }
        else {
            //判断各种生成概率 
            $bodyId = rand(1,2)==1?$fromSeed->bodyId:$partnerSeed->bodyId ;
            $faceId = rand(1,2)==1?$fromSeed->faceId:$partnerSeed->faceId ;
            $budId  = rand(1,2)==1?$fromSeed->budId:$partnerSeed->budId ;
            $newSeed = $this->addSeed( $bodyId,$faceId,$budId,1,SEED_FROM_BREED ) ;
            $variationMessage = '';
        }

        //生成新的种子 并放入到一号花园
        $newSeed -> saveLog("get","breed by [{$fromSeed->seedId}] and [{$partnerSeed->seedId}];Gold:-$result[spendGold] ;Money:-$result[spendMoney] ; EXP: $result[getExp] ;".$variationMessage);

        //成就判断
        $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_SEEDBREED);
        $achieveEvent->onAchieveComplete();
        //任务判断
        $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_SEEDBREED);
        $missionEvent->onMissionComplete();
        
        //返回新繁殖的种子对象
        $result['newSeed'] = $newSeed ;
        return $result ;
    }
    
    /**
     * 返回2个种子繁殖可能生成的种子类型
     * 
     * @param Seed Object $fromSeed
     * @param Seed Object $partnerSeed
     */
    public static function getBreedProbability ($fromSeed,$partnerSeed){
        $faceIds = $fromSeed->faceId!=$partnerSeed->faceId?array($fromSeed->faceId,$partnerSeed->faceId):array($fromSeed->faceId);
        $bodyIds = $fromSeed->bodyId!=$partnerSeed->bodyId?array($fromSeed->bodyId,$partnerSeed->bodyId):array($fromSeed->bodyId);
        $budIds  = $fromSeed->budId!=$partnerSeed->budId?array($fromSeed->budId,$partnerSeed->budId):array($fromSeed->budId);
        $resultData = array();
        foreach( $faceIds as $faceId ) {
            foreach( $bodyIds as $bodyId ) {
                foreach( $budIds as $budId ) {
                    $result = array(
                        'face'  => Yii::app()->objectLoader->load('SeedData',$faceId) ,
                        'body'  => Yii::app()->objectLoader->load('SeedData',$bodyId) ,
                        'bud'   => Yii::app()->objectLoader->load('SeedData',$budId) ,
                    );
                    $resultData[] = $result ;
                }
            }
        }
        return $resultData ;
    }

    /**
     * 计算2个种子繁殖需要消耗的金钱
     * @param Seed Object $fromSeed 
     * @param Seed Object $partnerSeed 
     *
     * @return int 
     */
    public static function getBreedNeedMoney($fromSeed,$partnerSeed) {
        return floor( ($fromSeed->getMaxGrowValue()+$partnerSeed->getMaxGrowValue())*0.02 ) ;
    }
    
    /**
     * 获得可以生成的种子名字列表
     * 
     * @param Seed Object $fromSeed
     * @param Seed Object $partnerSeed
     * 
     * @return string 
     */
    public static function getBreedNames($fromSeed,$partnerSeed){
        if( empty($fromSeed)||empty($partnerSeed) ) {
            return false ;
        }
        $names = array();
        foreach( self::getBreedProbability($fromSeed, $partnerSeed) as $result ) {
            $names[] = SeedData::getAllName($result['body']->dataId,$result['face']->dataId,$result['bud']->dataId);
        }
        //return implode( ' , ',$names );
        return $names ;
    }

    public static function getBreedData( $fromSeed,$partnerSeed ) {
        $seeds = array();
        foreach( self::getBreedProbability($fromSeed, $partnerSeed) as $result ) {
            $seeds[] = array(
                'name'  => SeedData::getAllName($result['body']->dataId,$result['face']->dataId,$result['bud']->dataId) ,
                'displayData'  => array (
                    Yii::app()->objectLoader->load('SeedData',$result['body']->dataId)->getDisplayData() ,
                    Yii::app()->objectLoader->load('SeedData',$result['face']->dataId)->getDisplayData() ,
                    Yii::app()->objectLoader->load('SeedData',$result['bud']->dataId)->getDisplayData() ,
                ),
            );
        }

        return array(
            'price' => self::getBreedNeedMoney($fromSeed,$partnerSeed) ,
            'seeds' => $seeds ,
            'level' => $partnerSeed->getMyLevel() ,
            'name'  => $partnerSeed->getName() ,
            'isChange'  => ($fromSeed->hideAttribute+$partnerSeed->hideAttribute>=95 && $fromSeed->hideAttribute+$partnerSeed->hideAttribute<=105)?1:0 ,
            'isLock'    => self::checkBreedLevel( $fromSeed,$partnerSeed )?0:1,
        );
    }


    public static function checkBreedLevel( $fromSeed,$partnerSeed ) {
        $player = Yii::app()->objectLoader->load('Player',$partnerSeed->playerId);
        try{
            $fromSeed->checkLevel( $player->level ); 
            $partnerSeed->checkLevel( $player->level ); 
            return true ;
        } catch ( Exception $e ) {
            //throw $e ;
            return false ;
        }
    }
    
    /**
     * 卖出一个种子
     * 
     * @param int $seedId
     * @return void
     */
    public function sellSeed($seedId){
        try {
            $transaction = Yii::app()->db->beginTransaction() ;
            $seed = Yii::app()->objectLoader->load('Seed',$seedId) ;
            $seed->checkOwner( $this->playerId );
            
            if( $seed->isFavourite() ) {
                $this->removeFavouriteSeed();
            }

            if( $seed->gardenId>0 ) {
                $gardenModel = Yii::app()->objectLoader->load('GardenModel',$this->playerId) ;
                $gardenModel->seedRemove($seedId,$seed->gardenId);
            }
            $player = Yii::app()->objectLoader->load('Player',$this->playerId);
            $getGold = $seed->getPrice() ;
            if( $getGold>0 ) {
                $player->addGold( $getGold, GOLD_SELL );
            }
            //消耗行动力 2012.7.4取消
            //$player->getPlayerPoint('actionPoint')->subValue(3) ;

            //可以获得的经验
            $getExp = floor((floor($player->level/10)+5)*1.5) ;
            $player->addExp( $getExp ); 

            $seed->saveLog("sell","Gold:$getGold ; EXP: $getExp");
            $seed->setSellState();

            //任务检查
            $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_SEEDSELL);
            $missionEvent->onMissionComplete();
            GlobalState::set( $this->playerId,'REFRESH_SEED',$seedId );
            $transaction -> commit();

            return array(
                'getGold' => $getGold,
                'getExp'  => $getExp,
            );
        }
        catch ( Exception $e ) {
            $transaction -> rollBack() ;
            throw $e ;
        }   
    }
    
    /**
     * 获得当前最爱的种子
     * 
     * @return Seed Object $seed
     */
    public function getFavouriteSeed(){
        /*
        $command = Yii::app()->db->createCommand('select * from seed where playerId=:playerId and favouriteFlag=1 limit 1');
        $seedId = $command->bindParam(':playerId',$this->playerId)->queryScalar();
        */
        $seedId = $player = Yii::app()->objectLoader->load('Player',$this->playerId)->favouriteSeed;
        return !empty($seedId)?Yii::app()->objectLoader->load('Seed',$seedId):null;
    }
	
    /**
     * 设置最爱的种子
     * 
     * @param int $seedId 种子ID
     * @return void 
     */
    public function setFavouriteSeed( $seedId ) {
        $seed = Yii::app()->objectLoader->load('Seed',$seedId);
        $seed -> checkOwner( $this->playerId );
        
        $this -> removeFavouriteSeed();

        $seed -> favouriteFlag = 1 ;
        $seed -> saveAttributes( array('favouriteFlag') );

        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $player -> favouriteSeed = $seedId ;
        $player -> saveAttributes( array('favouriteSeed') );

    }

    public function removeFavouriteSeed() {
        $currentFavouriteSeed = $this->getFavouriteSeed();
        if( !empty($currentFavouriteSeed) ) {
            $currentFavouriteSeed -> favouriteFlag = 0 ;
            $currentFavouriteSeed -> saveAttributes( array('favouriteFlag') );
            $player = Yii::app()->objectLoader->load('Player',$this->playerId);
            $player -> favouriteSeed = 0 ;
            $player -> saveAttributes( array('favouriteSeed') );
        }
    }
    
    /**
     * 重置最爱的种子
     * 
     */
    public function resetFavouriteSeed(){
        $command = Yii::app()->db->createCommand('select * from seed where playerId=:playerId and favouriteFlag=0 and isSell=0 limit 1');
        $seedId = $command->bindParam(':playerId',$this->playerId)->queryScalar();
        $this->setFavouriteSeed( $seedId );
    }
    

    public function fosterSeed( $seedId,$gardenId ) {
        $seed = Yii::app()->objectLoader->load('Seed',$seedId);
        //$seed->checkOwner( $this->playerId ); 
        if( $seed->isGrown() ) {
            throw new SException( Yii::t('Seed','you could not foster a grown seed') );
        }
        if( $seed->favouriteFlag==1 ) {
            $this->removeFavouriteSeed() ;
        }

        $seed->gardenId = $gardenId ;
        $seed->isFoster = 1 ;
        $seed->setGrowValue( 0,time() );
        $seed->saveAttributes( array('gardenId','isFoster') );

        //种子放置任务检查
        $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_SEEDPLANT, array('gardenId'=>$gardenId));
        $missionEvent->onMissionComplete();

        $seed->checkGlobalMessage();

        GlobalState::set( $this->playerId,'REFRESH_SEED',$seedId );
        GlobalState::set( $this->playerId,'FOSTER_STATE',1 );
    }

    /**
     * 
     * 
     */
    public function fosterSeedBack($seedId) {
        $seed = Yii::app()->objectLoader->load('Seed',$seedId);
        $seed->setGrowValue( 0,time() );
        $seed->gardenId = 0 ;
        $seed->isFoster = 0 ;
        $seed->saveAttributes( array('gardenId','isFoster') );

        GlobalState::set( $this->playerId,'REFRESH_SEED',$seedId );
        GlobalState::set($this->playerId,'FOSTER_STATE',0);
    }


    public function generateSeed( $from=SEED_FROM_SYSTEM,$seedLevel=0 ) {
        $levels = !empty($seedLevel)?$this->generateLevelArray($seedLevel):array();
        $seedData = SeedData::getPlayerUnlockData( $this->playerId,$levels ) ;
        $bodyId = array_rand( $seedData['body'] );
        $faceId = array_rand( $seedData['face'] );
        $budId = array_rand( $seedData['bud'] );
        return $this->addSeed( $bodyId,$faceId,$budId,0,$from );
    }
    
	public function generateSeed2( $from=SEED_FROM_SYSTEM, $seedMinLevel=0, $seedMaxLevel=0 ) {
        $seedData = SeedData::getRandomSeedData( $this->playerId, $seedMinLevel, $seedMaxLevel ) ;
        $bodyId = array_rand( $seedData['body'] );
        $faceId = array_rand( $seedData['face'] );
        $budId = array_rand( $seedData['bud'] );
        return $this->addSeed( $bodyId,$faceId,$budId,0,$from );
    }

    public function generateLevelArray( $level ) {
        $maxSize = $level*3 ;
        $resultKeys = array('face','bud','body');
        shuffle( $resultKeys );
        $result = array();
        foreach( $resultKeys as $key ) {
            $result[$key] = rand(1,$maxSize);
            $maxSize -= $result[$key];
        }
        return $result ;
    }

    public function generateFirstSeed() {
        $seed = $this->addSeed(1001,2001,3001,1,SEED_FROM_SYSTEM,array('hideAttribute'=>50));
        $seed->setGrowValue( $seed->getMaxGrowValue()-5 );
        return $seed ;
    }

    public function generateSeedByBodyId($bodyId,$growPeriod=SEED_GROW_PERIOD_NEW)
    {
        $seedData = SeedData::getPlayerUnlockData( $this->playerId ) ;
        $bodyId = $bodyId;
        $faceId = array_rand( $seedData['face'] );
        $budId = array_rand( $seedData['bud'] );
        $seed = $this->addSeed($bodyId, $faceId, $budId, 0, SEED_FROM_SYSTEM);
        
        $addGrowValues = array(
            SEED_GROW_PERIOD_GROWING => 0.1 ,
            SEED_GROW_PERIOD_GROWN   => 1 ,
        );
        if( isset($addGrowValues[$growPeriod]) ){
            $seed->setGrowValue( ceil($seed->getMaxGrowValue()*$addGrowValues[$growPeriod]) );
        }
        return $seed->seedId;
    }

    public function checkSeedExists( $bodyId=0,$faceId=0,$budId=0 ) {
        $sqlCondition = array();
        if( !empty($bodyId) ) {
            $sqlCondition[] = 'bodyId='.intval($bodyId);
        }
        if( !empty($faceId) ) {
            $sqlCondition[] = 'faceId='.intval($faceId);
        }
        if( !empty($budId) ) {
            $sqlCondition[] = 'budId='.intval($budId);
        }
        if( empty($sqlCondition) ){
            return 0 ;
        }
        $sql = "select * from seed where ".implode(' and ',$sqlCondition)." and playerId=".intval($this->playerId)." and state=0 and gardenId>0 and growPeriod>=".SEED_GROW_PERIOD_GROWN;
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryRow();
        return empty($result)?0:$result['seedId'];
    }


    public function generateBreedChangedSeed() {
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $catalog = Yii::app()->objectLoader->load('Catalog',$this->playerId);
        $keys = array('bodyId','budId','faceId');

        $seedData = SeedData::getPlayerUnlockData( $this->playerId ) ;
        
        $unlockData['bodyId'] = array_keys($seedData['body']);
        $unlockData['budId'] = array_keys($seedData['bud']);
        $unlockData['faceId'] = array_keys($seedData['face']);
        $currentData['bodyId'] = array_keys($catalog->getBodyAll());
        $currentData['budId'] = array_keys($catalog->getBudAll());
        $currentData['faceId'] = array_keys($catalog->getFaceAll());
        $randKeys = array();
        foreach( $keys as $i=>$key ) {
            $willData[$key] = array_diff( $unlockData[$key],$currentData[$key] ); 
            if( !empty($willData[$key]) ) {
                //shuffle($willData[$key]) ;
                $randKeys[] = $key ;
            }
        }

        if( !empty($randKeys) ) {
            $includeKey = array_rand( $randKeys );
        }
        else {
            $includeKey = -1 ;
        }
        
        foreach( $keys as $key ) {
            if( $includeKey>=0&&$randKeys[$includeKey]==$key ) {
                $randIndex = array_rand($willData[$key]);
                $$key = $willData[$key][$randIndex];
            }
            else {
                $randIndex = array_rand($currentData[$key]);
                $$key = $currentData[$key][$randIndex];
            }
        }
        //var_dump($currentData,$bodyId,$faceId,$budId);

        return $this->addSeed( $bodyId,$faceId,$budId,1,SEED_FROM_BREED_VARY) ;
    }


}
