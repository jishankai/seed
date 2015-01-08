<?php
/**
 * PlayerLevelUp
 **/
class PlayerLevelUp extends CBehavior
{
    private $_levelStage;
    private $_expAllLevels;

    public function __construct()
    {
        $this->_levelStage = Util::loadconfig('LevelStage');
        $this->_expAllLevels = Util::loadconfig('Exp');
    }

    public function addExp($exp)
    {
        if ($this->getOwner()->level >= PLAYER_LEVEL_MAX) {
            return;
        }
        $oldLevel = $this->getOwner()->level;

        $this->getOwner()->exp += $exp;
        $this->processLevelUp();

        $newLevel = $this->getOwner()->level;

        //if ($newLevel > $oldLevel) {
            // code...
        //}

        $this->getOwner()->saveAttributes(array('exp', 'level'));
    }

    public function processLevelUp()
    {
        if ($this->getOwner()->level >= PLAYER_LEVEL_MAX) {
			return;
        }

        $requiredExp = $this->nextLevelExp();
        if ($requiredExp <= $this->getOwner()->exp) {
            $this->getOwner()->level++;
            $this->getOwner()->exp-=$requiredExp;
			if ($this->getOwner()->level%10 == 0) {
				$this->getOwner()->inviteAward();
			}
			//成就检查
			$achieveEvent = new AchievementEvent($this->getOwner()->playerId, ACHIEVEEVENT_LEVEL, array('count'=>$this->getOwner()->level));
			$achieveEvent->onAchieveComplete();
			
            //升级的全局弹窗
            GlobalMessage::addLevelUp($this->getOwner()->playerId,$this->getOwner()->level);
            
            $this->processParamsChange();
            $this->processLevelUp();
        }
    }

    public function nextLevelExp()
    {
        return $this->getRequiredExp($this->getOwner()->level);
    }

    public function getRequiredExp($level)
    {

        return $this->_expAllLevels[$level];
    }

    public function processParamsChange()
    {
        //检查是否在虚拟好友处寄养种子
        VisualFriend::checkFosterSeedAsLevelUp($this->getOwner()->playerId);

        $supplyPower = $this->getOwner()->getPlayerPoint('supplyPower');
        $actionPoint = $this->getOwner()->getPlayerPoint('actionPoint');
        foreach ($this->_levelStage as $key) {
            if ($this->getOwner()->level === $key) {
                //花园上限+1
                //code...
            }

        }
        $actionPoint->addMax(ACTIONPOINT_MAX_INCREASE);
        $supplyPower->addMax(SUPPLYPOWER_MAX_INCREASE);
        $actionPoint->restoreToMax();

        //更新太阳能剩余时间的全局消息
        GlobalMessage::setPowerWarning($this->getOwner()->playerId); 

        //通知native更新相关数据
        GlobalState::set($this->getOwner()->playerId, array('PLAYER_EXP_MAX'=>$this->nextLevelExp(), 'ACTION_POINT_MAX'=>$actionPoint->getMax(), 'SUPPLY_POWER'=>$supplyPower->getRemainTime(), 'SUPPLY_POWER_MAX'=>$supplyPower->getMaxTime()));

        //是否有新任务可以接受
        MissionRecord::initNew($this->getOwner()->playerId);
    }

    public function getLevelUpData() {
        static $configData = array();
        if( empty($configData) ) {
            $configData = Util::loadConfig(__CLASS__);
        }
        
        $levelData = isset($configData[$this->getOwner()->level])?$configData[$this->getOwner()->level]:$configData[0] ;
        $resultData = array(
            'parts' => array(
                'body'  => empty($levelData['bodyId'])?null:Yii::app()->objectLoader->load('SeedData',$levelData['bodyId']),
                'face'  => empty($levelData['faceId'])?null:Yii::app()->objectLoader->load('SeedData',$levelData['faceId']),
                'bud'  => empty($levelData['budId'])?null:Yii::app()->objectLoader->load('SeedData',$levelData['budId']),
                'dress'  => empty($levelData['dressId'])?null:Yii::app()->objectLoader->load('SeedDressData',$levelData['dressId']),
            ),
            'newGarden' => $levelData['gardenCount']>0?1:0 ,
            'newMap' => $levelData['mapCount']>0?1:0 ,
        );
        $seedData = SeedData::getPlayerUnlockData( $this->getOwner()->playerId ) ;

        $randSize = 5 ;
        $bodyIds = empty($levelData['bodyId'])?array():array($levelData['bodyId']);
        $randBodyIds = (array)array_rand( $seedData['body'],min(count($seedData['body']),$randSize) ) ;
        $faceIds = empty($levelData['faceId'])?array():array($levelData['faceId']);
        $randFaceIds = (array)array_rand( $seedData['face'],min(count($seedData['face']),$randSize) ) ;
        $budIds  = empty($levelData['budId'])?array():array($levelData['budId']);
        $randBudIds  = (array)array_rand( $seedData['bud'],min(count($seedData['bud']),$randSize) );
        $dressIds = empty($levelData['dressId'])?array():array($levelData['dressId']);
        $randDressIds = (array)array_rand( $seedData['dress'],min(count($seedData['dress']),$randSize) );
        shuffle($randBodyIds) ;
        shuffle($randFaceIds) ;
        shuffle($randBudIds) ;
        shuffle($randDressIds) ;
        $tempSeeds = array();
        $isDataEmpty = empty($bodyIds)&&empty($faceIds)&&empty($budIds)&&empty($dressIds);

        $bodyIds = array_merge($bodyIds,$randBodyIds);
        $faceIds = array_merge($faceIds,$randFaceIds);
        $budIds = array_merge($budIds,$randBudIds);
        $dressIds = array_merge($dressIds,$randDressIds);

        foreach( $bodyIds as $bodyId ) {
            foreach( $faceIds as $faceId ) {
                foreach( $budIds as $budId ) {
                    foreach( $dressIds as $dressId ) {
                        $result = array(
                            'bodyId'    => $bodyId ,
                            'faceId'    => $faceId ,
                            'budId'     => $budId ,
                            'dressId'   => $dressId ,
                        );
                        $needFilter = true ;
                        foreach( $result as $k=>$v ) {
                            if( $isDataEmpty||$v==$levelData[$k] ) {
                                $needFilter = false ;
                                break ;
                            }
                        }
                        if( !$needFilter )
                        $tempSeeds[] = $result ;
                    }
                }
            }
        }
        shuffle($tempSeeds) ;
        $count = 0 ;
        foreach( $tempSeeds as $seed ) {
            $count ++ ;
            if( $count>5 ) break ;
            $resultData['seeds'][] = $seed ;
        }

        return $resultData ;
    }
}
?>
