<?php

/**
 * PlayerLoginModel
 * */
class PlayerLoginModel extends Model {

    public $playerId;
    private $_playerLogin;

    public function __construct($playerId) {
        $this->playerId = $playerId;
        $this->_playerLogin = Yii::app()->objectLoader->load('PlayerLogin', $this->playerId);
    }

    public function refreshLogin() {
        $session = Yii::app()->session;
        $session->open();
        $session['playerId'] = $this->playerId;

        $lastLoginTime = $this->_playerLogin->lastLoginTime;
        $currentTime = time();
        $loginDays = $this->_playerLogin->loginDays;
        if (empty($loginDays)) {
            if (Util::getDayTime($currentTime)==strtotime(date(SEED_GAMEDAY))) {
                $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_SPECIAL);
                $achieveEvent->onAchieveComplete(); 
            }
        }
        if (!isset($lastLoginTime) or (Util::getDayTime($currentTime) > Util::getDayTime($lastLoginTime))) {
            /*
              //���յ�һ�ε�½ 1)���ջ�Ծ�û���+1
              $playerLoginSummary = Yii::app()->objectLoader->load('PlayerLoginSummary', $currentTime);
              $playerLoginSummary->addActivePlayerNum();
             */
            if (!isset($lastLoginTime) or Util::getDayTime($lastLoginTime) == Util::getDayTime(strtotime('-1 days', $currentTime))) {
                $loginDays += 1;
            } else {
                $loginDays = 1;
            }
            
            PlayerLoginSummary::save('day', strtotime(date("Y/m/d")));
        }

        $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_LOGIN, array('count' => $loginDays));
        $achieveEvent->onAchieveComplete();

        if (isset($lastLoginTime) && Util::getDayTime($lastLoginTime) != Util::getDayTime($currentTime)) {
            $LoginRewardModel = Yii::app()->objectLoader->load('LoginRewardModel', $this->playerId);
            $LoginRewardModel->removeReward();
            $LoginRewardModel->setRandReward($loginDays);
        }

        if (!isset($lastLoginTime) or (Util::getMonthTime($currentTime) > Util::getMonthTime($lastLoginTime))) {

            PlayerLoginSummary::save('month', strtotime(date("Y/m/01")));
        }

        /*
          if (!isset($lastLoginTime) or (Util::getMonthTime($currentTime) > Util::getMonthTime($lastLoginTime))) {
          //���µ�һ�ε�½ 1)���»�Ծ�û���+1
          $playerLoginSummary_M = Yii::app()->objectLoader->load('PlayerLoginSummary_M', $currentTime);
          $playerLoginSummary_M->addActivePlayerNum();
          }
         */

        $this->_playerLogin->lastLoginTime = $currentTime;
        $this->_playerLogin->loginDays = $loginDays;
        $this->_playerLogin->saveAttributes(array('lastLoginTime', 'loginDays'));

//        return $this->getRewardCount();
    }

    public function getRewardCount() {
        //$loginAwardTime = $this->_playerLogin->loginAwardTime;
        $rewardCount = 0;
        /*
          if ($this->_playerLogin->loginDays == 0 or (isset($loginAwardTime) and Util::getDayTime($loginAwardTime) == Util::getDayTime(time()))) {
          return $rewardCount;
          }

          $rewardConfig = Util::loadconfig("reward");
          $keys = array_keys($rewardConfig);
          rsort($keys, SORT_NUMERIC);
          foreach ($keys as $key) {
          if ($this->_playerLogin->loginDays>=$key) {
          $rewardCount = $rewardConfig[$key];
          break;
          }
          }
         */

        return $rewardCount;
    }

    public function dailyLoginAward() {
        // code...
    }

    public function refuseReward() {
        
    }

}

?>
