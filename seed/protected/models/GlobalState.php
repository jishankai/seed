<?php

class GlobalState extends Model {
    public $playerId ;
    public $stateData = array() ;
    private $player ;

    private $stateTypes = array(
        'PLAYER_GOLD'       => 'refreshgold' ,
        'PLAYER_MONEY'      => 'refreshmoney' ,
        'PLAYER_EXP'        => 'refreshexp' ,
        'PLAYER_LEVEL'      => 'refreshlevel' ,
        'PLAYER_EXP_MAX'    => 'refreshmaxexp' ,
        'ACTION_POINT'      => 'refreshap' ,
        'ACTION_POINT_MAX'  => 'refreshmaxap' ,
        'ACTION_POINT_TIME' => 'refreshaptime' ,
        'SUPPLY_POWER'      => 'refreshenergy',
        'SUPPLY_POWER_MAX'  => 'refreshmaxenergy' ,
        'REFRESH_MAIL'      => 'refreshmail',
        'REFRESH_SEED'      => 'refreshseed',
        'REFRESH_MISSION'   => 'refreshmission',
        'NEW_USER_GUIDE'    => 'userguide',
        'USER_GUIDE_LEVEL'  => 'accessLevel',
        'GUIDE_MISSION'     => 'guideMission',
        'REPLACE_DECO_CUP'  => 'replacedeco',
        'NATIVE_CLOSE'      => 'close',
        'REWARD_INDEX'      => 'rewardIndex' ,
        'CURRENT_PLAYER'    => 'currentplayer',
        'NATIVE_ACTION'     => 'action' ,
        'MISSION_COUNT'     => 'missionCount',
        'MISSION_NEW'       => 'haveNewMission',
        'REFRESH_CUP'       => 'upgradecup',
        'FOSTER_STATE'      => 'fosterState',
        'HAVE_NEWMAIL'      => 'haveNewMail',
    );


    public function __construct($playerId){
        $this->playerId = $playerId ;
        $this->player = Yii::app()->objectLoader->load('Player',$playerId);
        $this->checkPlayer( $this->player );
    }

    public function checkPlayer($player) {
        if( !$player->isExists() ) {
            throw new CException('player not exists');
        }
    }

    public function setState( $key,$value=KEY_NOT_SET_VALUE ) {
        if( is_array($key) ) {
            $data = $key ;
        }
        else {
            $data = array( $key=>$value );
        }
        foreach( $data as $k=>$v ) {
            if( !isset($this->stateTypes[$k]) ) {
                continue ;
            }
            $this->stateData[$k] = $v ;
        }
    }

    public function getStateData() {
        return $this->stateData ;
    }

    public function getApiData() {
        $result = array();
        foreach( $this->stateData as $key=>$value ) {
            $result[$this->stateTypes[$key]] = $value ;
        }
        return $result ;
    }

    public static function set( $playerId,$key,$value=KEY_NOT_SET_VALUE ) {
        $state = Yii::app()->objectLoader->load('GlobalState',$playerId);
        $state -> setState ( $key,$value );
    }

}

