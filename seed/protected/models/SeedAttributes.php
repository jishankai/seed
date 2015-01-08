<?php
/**
 * 获得种子相关属性
 * 主要指的是需要通过公式计算获得的属性
 * 
 */
class SeedAttributes extends CBehavior {

    /**
     * 获得种子的价值
     * 
     * @return int
     */
    public function getPrice(){
        return  floor( $this->getGrowPercent(true)/100*$this->getMaxPrice() )  ;
    }
    
    /**
     * 获得种子的最大价值
     * 身体跟部分价值+装备价值
     * @return int
     */
    public function getMaxPrice(){
        $maxGrowValue = $this->getMaxGrowValue() ;
        if( empty($this->getOwner()->dressId) ) {
            return floor($maxGrowValue*0.12) ;
        }
        else {
            $bodyData =  Yii::app()->objectLoader->load('SeedData',$this->getOwner()->bodyId) ;
            $dressData =  Yii::app()->objectLoader->load('SeedDressData',$this->getOwner()->dressId) ;
            //成长值*（0.139+0.001*（装饰物稀有度-身体稀有度）
            return floor($maxGrowValue*(0.139+0.001*($dressData->level-$bodyData->level)) );
        }
    }
    
    /**
     * 获得种子的名字
     * 
     */
    public function getName(){
        $faceId = $budId = $dressId = 0 ;
        switch( $this->getOwner()->growPeriod ) {
            case SEED_GROW_PERIOD_EQUIPPED :
                $dressId= $this->getOwner()->dressId ;
            case SEED_GROW_PERIOD_GROWN :
                $budId= $this->getOwner()->budId ;
            case SEED_GROW_PERIOD_GROWING :
                $faceId= $this->getOwner()->faceId ;
            //case SEED_GROW_PERIOD_LITTLE :
            //case SEED_GROW_PERIOD_NEW :
            default :
                $bodyId= $this->getOwner()->bodyId ;
        }
        
        return SeedData::getAllName( $bodyId,$faceId,$budId,$dressId );
    }
    
    /**
     * 获得当前种子的等级
     * 
     */
    public function getLevel(){
        return Yii::app()->objectLoader->load('SeedData',$this->getOwner()->bodyId)->level ;
    }

    /**
     * 获得最大成长值
     * 
     */
    public function getMaxGrowValue(){
        if( !empty($this->getOwner()->maxGrowValue) ) {
            return $this->getOwner()->maxGrowValue ;
        }
        else {
           return self::getDataGrowValue($this->getOwner()->bodyId)+ 
                  self::getDataGrowValue($this->getOwner()->faceId)+ 
                  self::getDataGrowValue($this->getOwner()->budId)+ 
                  self::getDataGrowValue($this->getOwner()->dressId)  ;
        }
    }

    public static function getDataGrowValue( $dataId ) {
        return empty($dataId)?0:Yii::app()->objectLoader->load('SeedData',$dataId)->growValue ;
    }
	
    public static function getDataName( $dataId ) {
        return Yii::app()->objectLoader->load('SeedData',$dataId)->getName() ;
    }
    
    /**
     * 获得成长值的百分比
     * 
     * @return int
     */
    public function getGrowPercent( $isFloat=false ){
        $value = $this->getGrowValue()/$this->getMaxGrowValue()*100 ;
        return $isFloat?$value:floor($value) ;
    }
	    
    /**
     * 获得当前的成长值
     * 
     * @return int 
     */
    public function getGrowValue(){
        $maxValue = $this->getMaxGrowValue();
        if( $this->getOwner()->growValue>=$maxValue ){
            $growValue = $maxValue ;
        }
        else {
            /** 如果种子属于某个花园 通过时间等计算未增加的成长值 **/
            if( $this->getOwner()->gardenId>0 ){
                if (VisualFriend::checkOwnerByGarden($this->getOwner()->gardenId)) {
                    $garden = VisualFriend::getGarden($this->getOwner()->gardenId); 
                    $gapTime = time()-$this->getLastGrowTime();
                } else {
                    $garden = Yii::app()->objectLoader->load('Garden',$this->getOwner()->gardenId);
                    $player = Yii::app()->objectLoader->load('Player',$garden->playerId);
                    $lastGrowTime = $this->getLastGrowTime() ;
                    $gapTime = max(0,min( time()-$lastGrowTime, $player->getPlayerPoint('supplyPower')->getRemainTime($lastGrowTime) ) );
                }
                
                $addValue = $gapTime*$this->getGrowSpeed(true) ;
            }
            else {
                $addValue = 0 ;
            }
            
            $growValue = min($this->getMaxGrowValue(),$this->getOwner()->growValue + $addValue) ;
        }
        $newGrowPeriod = $this->_getGrowPeriod($growValue) ;
        if( $growValue>0 && $newGrowPeriod>$this->getOwner()->growPeriod ){
            $this->getOwner()->growPeriod = $newGrowPeriod ;
            //判断是否是虚拟好友
            if (!VisualFriend::checkOwnerByGarden($this->getOwner()->gardenId)) {
            $this->_saveGrowValue( $growValue );
            }
        }
        return floor( $growValue );
    }
    
    /**
     * 获得当前种子的成长速度
     * 
     */
    public function getGrowSpeed( $ignorePower=false ) {
        if( empty($this->getOwner()->gardenId) ) {
            return 0 ;
        }
        else {
            if (VisualFriend::checkOwnerByGarden($this->getOwner()->gardenId)) {
                $garden = VisualFriend::getGarden($this->getOwner()->gardenId);
            } else {
                $garden = Yii::app()->objectLoader->load('Garden',$this->getOwner()->gardenId);
                $player = Yii::app()->objectLoader->load('Player',$garden->playerId);
                if( !$ignorePower&&$player->getPlayerPoint('supplyPower')->getRemainTime()<=0 ) {
                    return 0 ;
                }
            }
            return $garden->decoExtraGrow/3600 ;
        }
    }

    
    /**
     * 获得成熟剩余的秒数
     * 
     */
    public function getGrowLimitSecond( $growPeriod=SEED_GROW_PERIOD_GROWN ) {
        $speed = $this->getGrowSpeed() ;
        $maxValue = $this->getMaxGrowValue();
        if( $growPeriod==SEED_GROW_PERIOD_GROWING ) {
            $maxValue = $maxValue*(SEED_GROW_LITTLE_PERCENT+0.1)/100;
        }
        return !empty($speed)?ceil(($maxValue-$this->getGrowValue())/$speed ):'-1';
    }
    

    
    /**
     * 获得成熟剩余的分钟数
     * 
     */
    public function getGrowLimitMinute() {
        return ceil( $this->getGrowSpeed()/60 );
    }
    
    /**
     * 获得刷新倒计时的秒数
     */
    public function getGrowRefreshSeconds() {
        return 60-(time() - $this->getLastGrowTime())%60;
    }


    /**
     * 是否已经成熟
     * 
     * @return bool true/false
     */
    public function isGrown(){
        return $this->getGrowValue()>=$this->getMaxGrowValue() ;
    }
    
    /**
     * 获得种子成长间隔的分钟数
     * 
     * @return int 
     */
    public function getGrowMinute(){
        $player = Yii::app()->objectLoader->load('Player',$this->getOwner()->playerId);
        $lastGrowTime = $this->getLastGrowTime() ;
        $gapTime = min( time()-$lastGrowTime, $player->getPlayerPoint('supplyPower')->getRemainTime($lastGrowTime) );
        return $gapTime>0?floor( $gapTime/60 ):0 ;
    }
    
    /**
     * 获得上次成长时间
     * 
     */
    public function getLastGrowTime(){
        return max( $this->getOwner()->lastGrowTime,$this->getOwner()->createTime );
    }
    
    /**
     * 保存种子成长值到DB 
     * 使用道具或成长速度变化时更新用
     * 
     * @param int $addValue 额外增加的量
     * @return void
     */
    public function setGrowValue( $addValue=0,$lastGrowTime=0 ){
        $newGrowValue = $this->getGrowValue()+$addValue ;
        if( $newGrowValue==0 || $this->getOwner()->growValue < $newGrowValue || (!empty($lastGrowTime)&&$this->getOwner()->lastGrowTime<$lastGrowTime) ) {
            $this->getOwner()->growPeriod = $this->_getGrowPeriod($newGrowValue) ;
            $this->_saveGrowValue( $newGrowValue,$lastGrowTime );
        }
        
    }

    private function _saveGrowValue( $growValue,$lastGrowTime=0 ) {
        if( empty($lastGrowTime) ) {
            $lastGrowTime = time() ; 
        }
        $maxValue = $this->getMaxGrowValue();
        $growValue = min($growValue,$maxValue) ;
        
        if( $this->getOwner()->lastGrowTime!=$lastGrowTime||$this->getOwner()->growValue != $growValue ) {
            $this->getOwner()->lastGrowTime = $lastGrowTime ;
            $this->getOwner()->growValue = $growValue ;
            $this->getOwner()->saveAttributes( array('growValue','growPeriod','lastGrowTime') );
            
            $this->getOwner()->checkGlobalMessage();
        }
        
    }

    private function _getGrowPeriod( $growValue ) {
        $maxValue = $this->getMaxGrowValue();
        $growValue = min($growValue,$maxValue) ;
        $growPercent = $growValue/$maxValue*100;
        if( $growPercent >= 10 && $this->getOwner()->growPeriod<SEED_GROW_PERIOD_GROWING  ) {
            $catalog = Yii::app()->objectLoader->load('Catalog',$this->getOwner()->playerId);
            $catalog -> addToList( $this->getOwner() );
        }
        /** 根据成长状态适时的更新相关值 **/
        if( $growPercent >= 100 && $this->getOwner()->growPeriod<SEED_GROW_PERIOD_GROWN ) {
            //任务检查
            $missionEvent = new MissionEvent($this->getOwner()->playerId, MISSIONEVENT_SEEDGROW);
            $missionEvent->onMissionComplete();
            $missionEvent = new MissionEvent($this->getOwner()->playerId, MISSIONEVENT_SEEDBUD, array('seedId'=>$this->getOwner()->seedId));
            $missionEvent->onMissionComplete();
            //种子身体任务检查
            $missionEvent = new MissionEvent($this->getOwner()->playerId, MISSIONEVENT_SEEDBODY, array('seedId'=>$this->getOwner()->seedId));
            $missionEvent->onMissionComplete();
            //随机种子任务检查
            $missionEvent = new MissionEvent($this->getOwner()->playerId, MISSIONEVENT_SEED, array('seedId'=>$this->getOwner()->seedId));
            $missionEvent->onMissionComplete();

            return SEED_GROW_PERIOD_GROWN ;
        }
        elseif( $growPercent >= 10 && $growPercent < 100 && $this->getOwner()->growPeriod<SEED_GROW_PERIOD_GROWING ) {
            $missionEvent = new MissionEvent($this->getOwner()->playerId, MISSIONEVENT_SEEDGROW);
            $missionEvent->onMissionComplete();
            return SEED_GROW_PERIOD_GROWING ;
        }
        elseif( $growValue > 0 && $growPercent < 10 && $this->getOwner()->growPeriod<SEED_GROW_PERIOD_LITTLE ) {
            return SEED_GROW_PERIOD_LITTLE ;
        }
        else {
            return $this->getOwner()->growPeriod ;
        }
    }
    
    /**
     * 增加喂养的N种属性
     * 
     * @param array $addAttributes
     * @return void 
     */
    public function addFeedAttributes( $addAttributes=array() ){
        $attributes = $this->getFeedAttributes();
        $addAttributes = self::formatFeedAttributes($addAttributes) ;
        foreach( $addAttributes as $key=>$val  ) {
            $attributes[$key] += $val ;
        }
        $this->setFeedAttributes($attributes,true) ;
    }
    
    /**
     * 处理并返回属性数组
     * 
     * @param array $attributes
     * @return array 
     */
    public function getFeedAttributes() {
        $attributes = empty($this->getOwner()->attributes)?array():unserialize($this->getOwner()->attributes);
        return self::formatFeedAttributes( $attributes ) ;
    }
    
    /**
     * 设置属性 
     * 
     * @param $array 要设置的属性内容
     * @param $isSave 是否保存
     */
    public function setFeedAttributes( $array,$isSave=false ) {
        $this->getOwner()->attributes = serialize($array) ;
        if( $isSave ) {
            $this->getOwner()->saveAttributes( array('attributes') );
        }
    }
    
    /**
     * 格式化/补全喂养属性 
     * 
     * @param array $attributes
     * @return array 
     */
    public static function formatFeedAttributes( $attributes ){
        $default_attibutes = array(
            SEED_ATTRIBUTE_1    => 0 ,
            SEED_ATTRIBUTE_2    => 0 ,
            SEED_ATTRIBUTE_3    => 0 ,
            SEED_ATTRIBUTE_4    => 0 ,
            SEED_ATTRIBUTE_5    => 0 ,
            SEED_ATTRIBUTE_6    => 0 ,
        );
        if( empty($attributes) ) {
            $attributes = $default_attibutes ;
        }
        else {
            foreach( $default_attibutes as $key=>$val ) {
                if(!isset($attributes[$key])) {
                    $attributes[$key] = $val ;
                }
            }
        }
        return $attributes ;
    }
    
    public function checkGrowDress() {
        if( $this->getOwner()->feedCount>0||$this->getOwner()->dressId>0 ) {
            return false ;
        }
        $attributeArray = $this->getFeedAttributes() ;

        $needValue = $this->getMaxAttributeValue();
        $dressList = SeedDressData::getDressByUnlockLevel( Yii::app()->objectLoader->load('Player',$this->getOwner()->playerId)->level );
        $ids = array();
        foreach( $dressList as $dressId => $dressData ) {
            if( $dressData->checkValueValid( $attributeArray,$needValue ) ) {
                $ids[] = $dressId ;
            }
            else {
                continue ;
            }
        }
        if( count($ids)>0 ) {
            $randKey = array_rand( $ids );
            $getDressId = $ids[$randKey]; 
        }
        else {
            $getDressId = 0 ;
        }
        //保存
        $this->setDressId( $getDressId ) ;
    }

    public function setDressId($dressId) {
        $this->getOwner()->dressId = $dressId ;
        //更新成长 状态
        $this->getOwner()->growPeriod = SEED_GROW_PERIOD_EQUIPPED ;
        $this->getOwner()->saveAttributes( array('dressId','growPeriod') );
        if( $dressId>0 ) { 
            //加入到图鉴
            $catalog = Yii::app()->objectLoader->load('Catalog',$this->getOwner()->playerId);
            $catalog -> addToList( $this->getOwner() );
            //记录Log 
            $this->getOwner()->saveLog('equip','dressId:'.$dressId);
        }
        else {
            //do nothing 
        }
    }

    public function getMyLevel() {
        return max(1,floor( (
                Yii::app()->objectLoader->load('SeedData',$this->getOwner()->bodyId)->level + 
                Yii::app()->objectLoader->load('SeedData',$this->getOwner()->faceId)->level + 
                Yii::app()->objectLoader->load('SeedData',$this->getOwner()->budId)->level 
                )/3) 
            ) ;
    }

    public function getAttributeLevels() {
        $configData = Util::loadConfig('SeedBodyAttributes');
        $maxValue =  $configData[$this->getOwner()->bodyId]; 
        $result = array();
        foreach( $this->getFeedAttributes() as $k=>$v ) {
            $result[$k] = floor( $v/$maxValue*SeedDressData::getMaxValue() );
        }
        return $result ;
    }
    

    public function getMaxAttributeValue() {
        $configData = Util::loadConfig('SeedBodyAttributes');
        if( !isset($configData[$this->getOwner()->bodyId]) ){
            throw new CException('Unknown Seed');
        }
        return $configData[$this->getOwner()->bodyId];
    }

    public function getBreedCDTime() {
        return max($this->getOwner()->breedCDTime-time(),0) ;
    }
    public function saveBreedCDTime() {
        $this->getOwner()->breedCDTime = SEED_BREED_CD_TIME*60+time() ;
        $this->getOwner()->saveAttributes( array('breedCDTime') );
    }

}
