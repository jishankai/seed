<?php
/**
 * 玩家相关属性
 **/
class PlayerAttributes extends CBehavior
{
    public function setDefaultGarden($gardenId) {
        $this->getOwner()->defaultGarden = $gardenId;
        return $this->getOwner()->saveAttributes(array('defaultGarden'));
    }

    public function setFavouriteGarden($gardenId) {
        $this->getOwner()->favouriteGarden = $gardenId;
        return $this->getOwner()->saveAttributes(array('favouriteGarden'));
    }

    public function getFavouriteSeed() {
        return $this->getOwner()->favouriteSeed;
    }

    public function checkGold($value)
    {
        return $this->getOwner()->gold >= $value;
    }

    public function addGardenNum()
    {   
        $this->getOwner()->gardenNum++;
        return $this->getOwner()->saveAttributes(array('gardenNum'));
    }

    public function addGold($value, $from=GOLD_SYSTEM) {
        if ($this->getOwner()->gold <= GOLD_MAX) {
            if( empty($value)||$value<0 ) {
                return false ;
                //throw  new CException('add value must greater than zero');
            }

            if ($this->getOwner()->gold+$value>GOLD_MAX) {
                $sql = "update player set gold=".GOLD_MAX." where playerId='{$this->getOwner()->playerId}'";
            } else {
                $sql = "update player set gold=gold+$value where playerId='{$this->getOwner()->playerId}'";
            }

            $cmd = Yii::app()->db->createCommand($sql);
            $effectCount = $cmd -> execute();
            if ($effectCount == 0) {
                $m = new GlobalMessage($this->getOwner()->playerId);
                $m->addMessage(Yii::t('Player', 'gold has not been added'));
            }
            $this->getOwner()->reset();
            GlobalState::set($this->getOwner()->playerId, 'PLAYER_GOLD', $this->getOwner()->gold);

            //获得新绿之叶成就
            $achieveEvent = new AchievementEvent($this->getOwner()->playerId, ACHIEVEEVENT_GOLD, array('value'=>$value));
            $achieveEvent->onAchieveComplete();
            //任务检查
            $missionEvent = new MissionEvent($this->getOwner()->playerId, MISSIONEVENT_GOLD, array('value'=>$value));
            $missionEvent->onMissionComplete();

            //贩卖获得新绿之叶成就
            if ($from==GOLD_SELL) {
                $achieveEvent = new AchievementEvent($this->getOwner()->playerId, ACHIEVEEVENT_EARNGOLD, array('value'=>$value));
                $achieveEvent->onAchieveComplete();
            }
        } else {
            $m = new GlobalMessage($this->getOwner()->playerId);
            $m->addMessage(Yii::t('Player', 'your gold has reached the maxinum'));
        }

    }

    public function subGold($value)
    {
        if (self::checkGold($value)) {
            $sql = "update player set gold=gold-$value where playerId='{$this->getOwner()->playerId}'";
            $cmd = Yii::app()->db->createCommand($sql);
            $effectCount = $cmd -> execute();
            if ($effectCount == 0) {
                throw new SException(Yii::t('View','gold has not been subed'));
            }
            $this->getOwner()->reset();
            GlobalState::set($this->getOwner()->playerId, 'PLAYER_GOLD', $this->getOwner()->gold);

        } else {
            throw new SException('gold is not enough', EXCEPTION_TYPE_GOLD_NOT_ENOUGH);//金币不足
        }

    }
}
?>
