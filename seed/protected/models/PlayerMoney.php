<?php

class PlayerMoney extends UserMoney {
    public $playerId;
    
    public function __construct($playerId){
        $this->playerId = $playerId ;
        $player = Yii::app()->objectLoader->load('Player',$playerId);
        $this->checkPlayer($player);
        parent::__construct( $player->userId );
    }

    public function checkPlayer($player) {
        if( !$player->isExists() ) {
            throw new CException('player not exists');
        }
    }

    /**
     * Send systemGold to player 
     * adminitrators or system send systemGold use this method
     */
    public function send($value,$comment,$goodsId=0){
        if (($this->userGold+$value)<=USERMONEY_MAX) {
            parent::send($value,$comment,$goodsId);

            GlobalState::set($this->playerId, 'PLAYER_MONEY', $this->getMoney());

            //Achievement
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_MONEY, array('value'=>$value));
            $achieveEvent->onAchieveComplete();
        } else {
            $m = new GlobalMessage($this->playerId);
            $m->addMessage(Yii::t('Player', 'your usermoney has reached the maxinum'));
        }
        GlobalState::set( $this->playerId,'PLAYER_MONEY',$this->getMoney() );
    }

    /**
     * Add player Gold.
     * user payment use this method.
     */
    public function add($value,$comment,$goodsId=0){
        if (($this->userGold+$value)<=USERMONEY_MAX) {
            parent::add($value,$comment,$goodsId);

            GlobalState::set($this->playerId, 'PLAYER_MONEY', $this->getMoney());

            //Achievement
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_MONEY, array('value'=>$value));
            $achieveEvent->onAchieveComplete();
        } else {
            $m = new GlobalMessage($this->playerId);
            $m->addMessage(Yii::t('Player', 'your usermoney has reached the maxinum'));
        }
        GlobalState::set( $this->playerId,'PLAYER_MONEY',$this->getMoney() );
    }

    public function reduce($value,$comment,$goodsId=0) {
        parent::reduce($value,$comment,$goodsId);
        GlobalState::set( $this->playerId,'PLAYER_MONEY',$this->getMoney() );
    }
}
?>
