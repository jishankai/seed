<?php
/**
 * 种子基础类
 * 
 * 
 */
class Seed extends RecordModel {
    public $seedId ; 
    
    public function __construct($seedId){
        $this->seedId = $seedId ;
        $this->attachBehavior('SeedAttributes',new SeedAttributes );
        $this->attachBehavior('SeedDisplayData',new SeedDisplayData );
    }
    
    /**
     * 可以访问的种子属性字段
     * 
     */
    public static function attributeColumns(){
        return array(
                'playerId','gardenId','favouriteFlag','bodyId','faceId','budId',
                'dressId','attributes','growValue','maxGrowValue','lastGrowTime','growPeriod',
                'feedCount','breedCDTime','isFoster','isSell','sellTime','createTime',
                'getFrom','hideAttribute','state',
            );
    }
    
    /**
     * (non-PHPdoc)
     * @see protected/components/RecordModel::loadData()
     * 
     */
    protected function loadData(){
        $command = Yii::app()->db->createCommand('select * from seed where seedId=:seedId');
        $rowData = $command->bindParam(':seedId', $this->seedId)->queryRow();
        return $rowData ;
    }
    
    /**
     * (non-PHPdoc)
     * @see protected/components/RecordModel::saveData()
     */
    protected function saveData( $attributes=array() ){
        return DbUtil::update(Yii::app()->db, 'seed', $attributes, array('seedId'=>$this->seedId)) ;
    }
    
    /**
     * 批量载入数据
     * 
     * @param array $params 
     */
    public static function multiLoad( $params=array(),$isSimple=true ){
        $sql = "SELECT * FROM seed";
        $conditions = array();
        $bindValues = array() ;
        if( isset($params['playerId']) ) {
            $conditions[] = 'playerId=:playerId';
            $bindValues[':playerId'] = $params['playerId'] ;
        }
        
        if( isset($params['gardenId']) ) {
            $conditions[] = 'gardenId=:gardenId';
            $bindValues[':gardenId'] = $params['gardenId'] ;
        }
        
        if( isset($params['seedIds'])&&is_array($params['seedIds']) ) {
            $conditions[] = 'seedId IN ('.implode(',', $params['seedIds']).')';
        }
        if( isset($params['isSell']) ) {
            $conditions[] = 'isSell='.$params['isSell'];
        }
        
        if( !empty($conditions) ){
            $sql .= ' WHERE '.implode(' AND ',$conditions);
        }
        
        return self::multiLoadBySql($sql,'seedId',$bindValues,$isSimple);
    }
    
    public static function create( $createInfo ) {
        $insertArr = array();
        foreach(self::attributeColumns() as $key){
            if(isset($createInfo[$key])){
                $insertArr[$key] = $createInfo[$key];
            }
            else {
                $insertArr[$key] = 0 ;
            }
        }
        $insertArr['createTime'] = time();
        return DbUtil::insert(Yii::app()->db, 'seed', $insertArr, true);
        
    }
    
    

    
    /**
     * 判断种子是否属于某个player 
     * 
     * @param int $playerId
     * @return bool 是否 
     */
    public function isOwner($playerId){
        return $this->playerId == $playerId ? true:false ;
    }
    
    /**
     * 如果种子不属于player 会抛出异常
     * 
     * @return void
     */
    public function checkOwner($playerId){
        if( !$this->isOwner($playerId) ) {
            throw new SException( Yii::t('Seed','the seed not belong you') ) ;
        }
    }
    
    /**
     * 更新种子所在的花园
     * 
     * @param int $gardenId
     * @return void
     */
    public function updateGarden($gardenId){
        $this->gardenId = $gardenId ;
        $this->saveAttributes( array('gardenId') );
        GlobalState::set( $this->playerId,'REFRESH_SEED',$this->seedId );
    }
    
    public function setSellState(){
        $this->isSell = 1 ;
        $this->sellTime = time(); 
        $this->state = SEED_STATE_SOLD; 
        $this->saveAttributes( array('isSell','sellTime','state') );

        $this->checkGlobalMessage();
    }
    
    
    public function isFavourite(){
        return $this->favouriteFlag==1?true:false ;
    }

    public function saveLog( $actionType,$actionDesc,$playerId=0 ) {
        if( empty($playerId) ) {
            $playerId = $this->playerId ;
        }
        return SeedActionLog::save( $this->seedId,$actionType,$actionDesc,$playerId );
    }

    public function checkExists() {
        if( !$this->isExists() ) {
            throw new SException( Yii::t('Seed','seed not exists') );
        }
    }

    public function isExists() {
        return parent::isExists()&&!$this->isSell ;
    }
    
    public static function getFosterSeed( $playerId,$gardenId ) {
        $sql = "select * from seed where playerId=:playerId and gardenId=:gardenId and isFoster=1";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->bindParam( ':playerId',$playerId )->bindParam( ':gardenId',$gardenId )->queryRow();
        if( empty($result['seedId']) ) {
            return false ;
        }
        else {
            $seed = Yii::app()->objectLoader->load('Seed',$result['seedId']);
            return $seed ;
        }
    }

    public static function FosterSeedNum($playerId) {
        $command = Yii::app()->db->createCommand('SELECT count(*) FROM seed WHERE playerId = :playerId AND isFoster = 1 and isSell=0');
        $command->bindParam(':playerId', $playerId);
        return $command->queryScalar(); 
    }

    /**
     * 检查种子关联的全局消息状态
     */
    public function checkGlobalMessage() {
        if( $this->state<0 || $this->growPeriod>=SEED_GROW_PERIOD_GROWN || $this->getGrowLimitSecond()<=0 ) {
            $this->removeGlobalMessage(MESSAGE_TYPE_SEED_MOVABLE);
            $this->removeGlobalMessage(MESSAGE_TYPE_SEED_GROWN);
        }
        else {
            $currentTime = time();

            $this->addGlobalMessage($currentTime+$this->getGrowLimitSecond(),MESSAGE_TYPE_SEED_GROWN);
            if( $this->getOwner()->growPeriod<SEED_GROW_PERIOD_GROWING ) {
                $this->getOwner()->addGlobalMessage( $currentTime+$this->getGrowLimitSecond(SEED_GROW_PERIOD_GROWING),MESSAGE_TYPE_SEED_MOVABLE );
            }
        }
    }

    public function addGlobalMessage( $showTime,$type ) {
        if( $this->state>=0 ) {
            $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$this->playerId);
            $globalMessage -> addMessage( $this->seedId,$type,array('showTime'=>$showTime) );
        }
    }

    public function removeGlobalMessage( $type ) {
        $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$this->playerId);
        $globalMessage -> removeMessage( $this->seedId,$type );
    }

    public function getGlobalMessage() {
        $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$this->playerId);
        if( isset($globalMessage->messageData[MESSAGE_TYPE_SEED_MOVABLE][$this->seedId])&&$globalMessage->messageData[MESSAGE_TYPE_SEED_MOVABLE][$this->seedId]['showTime']>time() ) {
            return $globalMessage->messageData[MESSAGE_TYPE_SEED_MOVABLE][$this->seedId] ;
        }
        elseif( isset($globalMessage->messageData[MESSAGE_TYPE_SEED_GROWN][$this->seedId])&&$globalMessage->messageData[MESSAGE_TYPE_SEED_GROWN][$this->seedId]['showTime']>time() ) {
            return $globalMessage->messageData[MESSAGE_TYPE_SEED_GROWN][$this->seedId] ;
        }
        else {
            return false ;
        }
    }

    public static function getInstance( $seedId ) {
        //是否为虚拟好友
        $id = VisualFriend::checkOwnerBySeed($seedId);
        if ($id) {
            $key = VisualFriend::createKey($id, $this->playerId);
            $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
            return $visualFriend->getSeed($seedId);
        }
        else {
            return Yii::app()->objectLoader->load( 'Seed',$seedId );
        }
    }

    public static function isVisualSeed( $seedId ) {
        return VisualFriend::checkOwnerBySeed($seedId);
    }

    public function checkLevel( $level,$isBool=false ) {
        try{
            Yii::app()->objectLoader->load('SeedData',$this->bodyId)->checkLevel($level);
            Yii::app()->objectLoader->load('SeedData',$this->faceId)->checkLevel($level);
            Yii::app()->objectLoader->load('SeedData',$this->budId)->checkLevel($level);
            return true ;
        } catch (Exception $e) {
            if( $isBool ) {
                return false ;
            }
            else {
                throw $e ;
            }
        }
    }

}