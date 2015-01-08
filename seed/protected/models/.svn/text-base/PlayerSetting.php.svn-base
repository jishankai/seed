<?php

class PlayerSetting extends Model {
    public $playerId ;
    public $settingData ;
    private $player ;

    private $settingTypes = array(
        'soundFlag'     => 1 ,
        'musicFlag'     => 1 ,
        'seedMoveableFlag'     => 1 ,
        'seedGrownFlag'     => 1 ,
        'achievementFlag'     => 1 ,
        'newGiftFlag'     => 1 ,
    );
/*
define('MESSAGE_TYPE_SEED_GROWN',2);    //种子成熟消息
define('MESSAGE_TYPE_SEED_MOVABLE',3);  //种子可以移动的消息
define('MESSAGE_TYPE_NEW_MISSION',4);   //获得新的任务
define('MESSAGE_TYPE_NEW_MAIL',5);      //获得新的邮件
define('MESSAGE_TYPE_MAIL_GIFT',6);     //邮箱礼物提醒
define('MESSAGE_TYPE_ACHIEVE_COMPLETE',7);  //成就达成
define('MESSAGE_TYPE_POWER_WARNING',8); //太阳能不足
define('MESSAGE_TYPE_LEVEL_UP',9);      //玩家升级
define('MESSAGE_TYPE_FRIEND_INVITE',10);//好友邀请
define('MESSAGE_TYPE_ITEM_FULL',11);//仓库满了
*/
    private $settingsInGame = array(
        /*
        'seedMoveable' => 1,
        'seedGrown'  => 1,
        //'newMail' => 1,
        'achieveComplete' => 1,
        'powerWarning' => 1,
        //'friendInvite' => 1,
        */
    );

    private $settingsOutGame = array(
        /*
        'APWarning' =>array(
            'flag'  => 1 ,
            'start' => 0,
            'end'   => 24,
        ) ,
        'allSeedGrown' =>array(
            'flag'  => 1 ,
            'start' => 0,
            'end'   => 24,
        ) ,
        'powerWarning' =>array(
            'flag'  => 1 ,
            'start' => 0,
            'end'   => 24,
        ) ,
        'gameOpration' =>array(
            'flag'  => 1 ,
            'start' => 0,
            'end'   => 24,
        ) ,*/
    );

    private $outGameAttributes = array(/*
        'flag'  => 1 ,
        'start' => 0,
        'end'   => 24,*/
    );
    
    
    public function __construct($playerId){
        $this->playerId = $playerId ;
        $this->player = Yii::app()->objectLoader->load('Player',$playerId);
        $this->checkPlayer( $this->player );
        $this->setSettingData();
    }

    public function checkPlayer($player) {
        if( !$player->isExists() ) {
            throw new CException('player not exists');
        }
    }

    public function getSettingData( $settingType=0 ) {
        return $this->settingData ;
    }

    public function getDefaultData() {
        $defaultData = $this->settingTypes ;
        //$defaultData['inGame'] = $this->settingsInGame ;
        //$defaultData['outGame'] = $this->settingsOutGame ;
        return $defaultData ;
    }

    public function saveSettingData( $settingData=array() ) {
        if( !empty($settingData) ) {
            $this->setSettingData( $settingData );
        }
        $this->player->settingData = serialize( $this->settingData );
        $this->player->saveAttributes( array('settingData') );
    }

    private function setSettingData( $settingData=array() ) {
        if( empty($settingData) ) {
            $settingData = !empty($this->player->settingData)?unserialize($this->player->settingData):array();
        }
        foreach( $this->settingTypes as $key=>$defaultValue ) {
            $this->settingData[$key] = isset($settingData[$key])?$this->getKeyValue($key,$settingData[$key]):$defaultValue;
        }
        foreach( $this->settingsInGame as $key=>$value ) {
            $this->settingData['inGame'][$key] = empty($settingData['inGame'][$key])?0:1;
        }
        foreach( $this->settingsOutGame as $key=>$value ) {
            if( empty($settingData['outGame'][$key]) ) {
                $this->settingData['outGame'][$key] = $value ;
            }
            else {
                foreach( $value as $k=>$v ) {
                    $this->settingData['outGame'][$key][$k] = isset($settingData['outGame'][$key][$k])?$this->getKeyValue($k,$settingData['outGame'][$key][$k]):$v;
                }
            }
        }
    }
    
    private function getKeyValue( $key,$value ) {
        switch( $key ) {
            case 'soundFlag' :
            case 'musicFlag' :
            case 'seedMoveableFlag':
            case 'seedGrownFlag':
            case 'achievementFlag':
            case 'newGiftFlag':
            case 'flag' :
                    return empty($value)?0:1 ;
            case 'musicPercent' :
            case 'soundPercent' :
                return min(95,max(0,$value));
            case 'begin' :
            case 'end' :
                return min(24,max(0,$value));
            default : 
                return $value ;
        }
    }



}

