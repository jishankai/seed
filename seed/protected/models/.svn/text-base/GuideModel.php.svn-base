<?php
class GuideModel extends Model{
    public $playerId ;
    
    private $guideData ;
    

    public function __construct( $playerId ) {
        $this->playerId = $playerId ;
        $this->guideData = Util::loadConfig('UserGuide');
    }

    public function getGuideInfo(){
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $guideLevel  = $player->getStatus('guideLevel');
        if( $guideLevel>124 ) return false;
        $completed = $player->getStatus('guideCompleted');
        foreach( $this->guideData as $index=>$guideInfo ) {
            if( (!empty($guideInfo['missionId'])&&$completed>0&&$this->getMissionStatus($guideInfo['missionId'])<=MISSIONRECORD_COMPLETED)||($index+$guideInfo['steps']-1>$guideLevel && $player->level>=$guideInfo['level']) ) {
                $currentIndex = max(10,floor($player->getStatus('guideLevel')/10)*10);
                $guideInfo['index'] = $index ;
                return $guideInfo ;
            }
        }
        return false ;
    }

    public function saveStatus( $accessLevel=0 ) {
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $guideInfo = $this->getGuideInfo();
        if( !empty($guideInfo) ) {
            if( empty($accessLevel) ){
                $accessLevel = $guideInfo['index']+$guideInfo['steps'];
            }
            if( floor($guideInfo['index']/10)==floor($accessLevel/10) ) {

                $player->setStatus('guideLevel',$accessLevel);
                //万物之初任务检查
                if ($guideInfo['index']==10) {
                    $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_TUTORIAL);
                    $missionEvent->onMissionComplete();
                }
                if( $guideInfo['index'] == 30 ) {
                    MissionRecord::initNew($this->playerId);
                }
            }
        }
    }

    public function checkGuideUrl( $controllerName,$actionName ) {
        if( !SeedConfig::isUserGuide() ) return true ;
        $guideInfo = $this->getGuideInfo();
        if( isset( $guideInfo['actionRules'] ) ) {
            $actionRules = new ActionRules($guideInfo['actionRules']);
            return $actionRules->checkRules( $controllerName,$actionName );
        }
        return true ;
    }

    public function isCurrentGuide( $accessLevel ) {
        $guideInfo = $this->getGuideInfo();
        return empty($guideInfo)||floor($guideInfo['index']/10)!=floor($accessLevel/10)?0:1;
    }

    public function checkMissionState( $missionId ) {
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $guideLevel = $player->getStatus('guideLevel');
        $currentIndex = max(10,floor($guideLevel/10)*10);
        $completed = $player->getStatus('guideCompleted');
        /*if( $this->isMissionCompleted($currentIndex) ) {
            $guideInfo = $this->getGuideInfo();
            $currentIndex = $guideInfo['index'];
        }*/
        
        if( !empty($this->guideData[$currentIndex]['missionId'])&&$this->guideData[$currentIndex]['missionId']==$missionId ){
            $player->setStatus('guideCompleted',$currentIndex);
        }
        else {
            //do nothing
        }
    }

    public function isMissionCompleted( $currentIndex=0 ) {
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        if( empty($currentIndex) ) {
            $currentIndex = max(10,floor($player->getStatus('guideLevel')/10)*10);
        }
        $completed = $player->getStatus('guideCompleted');
        $missionId = empty($this->guideData[$currentIndex]['missionId'])||empty($completed)||$currentIndex<$completed?0:$this->guideData[$currentIndex]['missionId'];
        $missionStatus = $this->getMissionStatus( $missionId );
        return  $missionStatus==MISSIONRECORD_COMPLETED?$missionId:0;
    }

    public function saveAccessLevel( $accessLevel=0 ) {
        if( empty($accessLevel) ){
            $guideInfo = $this->getGuideInfo();
            $accessLevel = $guideInfo['index']+$guideInfo['steps'];
        }
        Yii::app()->objectLoader->load('Player',$this->playerId)->setStatus('guideLevel',$accessLevel);
    }

    public static function getAccessLevel( $playerId ) {
        $player = Yii::app()->objectLoader->load('Player',$playerId);
        $guide = Yii::app()->objectLoader->load('GuideModel',$playerId);
        $guideInfo = $guide->getGuideInfo();
        return max($player->getStatus('guideLevel'),(empty($guideInfo)?0:$guideInfo['index']));
    }

    public static function getGuideState( $playerId ) {
        if( !SeedConfig::isUserGuide() ) return 0 ;
        $player = Yii::app()->objectLoader->load('Player',$playerId);
        $guide = Yii::app()->objectLoader->load('GuideModel',$playerId);
        $guideInfo = $guide->getGuideInfo();
        return empty($guideInfo)||$guideInfo['type']=='path'||in_array($player->getStatus('guideLevel'),array(20,31,85,121))?0:1 ;
    }
    
    public static function checkGuideState( $playerId ) {
        GlobalState::set( $playerId,self::getGuideStateData($playerId) );
    }

    public static function getGuideStateData( $playerId ) {
        $player = Yii::app()->objectLoader->load('Player',$playerId);
        $guide = Yii::app()->objectLoader->load('GuideModel',$playerId);
        $guideInfo = $guide->getGuideInfo();
        if( !empty( $guideInfo ) ) {
            $guideLevel = $player->getStatus('guideLevel');
            if( $guideLevel==39 ) {
                $missionCompleted = 2 ;
            }
            else {
                $missionCompleted = $guide->isMissionCompleted() ;
            }
            $guideLevel = max($guideInfo['index'],$guideLevel);
            return array(
                'NEW_USER_GUIDE' => self::getGuideState( $playerId ) ,
                'USER_GUIDE_LEVEL' => $guideLevel ,
                'GUIDE_MISSION'  => $missionCompleted ,
            );
        }
        else {
            return array();
        }
    }

    public function getMissionStatus( $missionId ) {
        $sql = "select * from missionRecord where playerId=:playerId and missionId=:missionId";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->bindParam(':playerId',$this->playerId)->bindParam(':missionId',$missionId)->queryRow();
        return empty($result)?-1:$result['status'];
    }

    public function isNewUserGuide() {
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        return $player->getStatus('guideLevel')<124?1:0 ;
    }
}