<?php

class LoginRewardModel extends Model {

    private $player;
    private $playerId;
    private $playerLogin;

    public function __construct($playerId) {
        $this->playerId = $playerId;
        $this->player = Yii::app()->objectLoader->load('Player', $this->playerId);
        $this->playerLogin = Yii::app()->objectLoader->load('PlayerLogin', $this->playerId);
        $this->attachBehavior('RewardAttributes', new RewardAttributes);
    }

    public function getRewardInfo() {
        $rewardInfo = unserialize($this->playerLogin->rewardInfo);
        if (empty($rewardInfo)) {
            $rewardInfo = array();
        }
        return $rewardInfo;
    }

    public function getRewardIndex($numIndex) {
        $count = 0;
        $rewardInfo = unserialize($this->playerLogin->rewardInfo);
        if (!empty($rewardInfo)) {
            foreach ($rewardInfo as $index => $reward) {
                $count++;
                if ($count == $numIndex) {
                    return $index;
                }
            }
        }
    }

    public function setRewardInfo($rewardInfo) {
        $this->playerLogin->rewardInfo = serialize($rewardInfo);
    }

    public function setReward() {
        $lastLoginTime = $this->playerLogin->lastLoginTime;
        $loginDays = $this->playerLogin->loginDays;
        $currentTime = time();
        if (!isset($lastLoginTime) or Util::getDayTime($lastLoginTime) == Util::getDayTime(strtotime('-1 days', $currentTime))) {
            $loginDays += 1;
        } else {
            $loginDays = 1;
        }
        $this->_playerLogin->lastLoginTime = $currentTime;
        $this->_playerLogin->loginDays = $loginDays;
        $this->_playerLogin->saveAttributes(array('lastLoginTime', 'loginDays'));
        $this->removeReward();
    }

    public function setRandReward($loginDays) {

        if ($loginDays == 1) {
            $num = 1;
        } else if ($loginDays > 1 && $loginDays < 5) {
            $num = 2;
        } else if ($loginDays >= 5) {
            $num = 3;
        }
        for ($i = 1; $i <= $num; $i++) {
            //$initRandArr = $this->initRandArr();
            //$randDisorder = $this->randDisorder($initRandArr);
            mt_srand((double) microtime() * 1000000);
            $randindex = mt_rand(1, 1000);
            //$randType = $randDisorder[$randindex];
            if ($randindex <= 500)
                $index = $this->randItem();
            if (500 < $randindex && $randindex <= 800)
                $index = 101;
            if (800 < $randindex && $randindex <= 950)
                $index = 102;
            if (950 < $randindex && $randindex <= 970)
                $index = 103;
            if (970 < $randindex && $randindex <= 990)
                $index = 104;
            if (990 < $randindex && $randindex <= 995)
                $index = 105;
            if (995 < $randindex && $randindex <= 1000)
                $index = 106;
            $this->addReward($index);
        }
    }

    public function randItem() {
        $playerlevel = $this->player->level;
        $itemlevel = 0;
        $itemId = 0;
        $flag = true;
        if ($playerlevel >= 1 && $playerlevel <= 10)
            $itemlevel = 1;
        if ($playerlevel > 10 && $playerlevel <= 25)
            $itemlevel = 2;
        if ($playerlevel > 25 && $playerlevel <= 45)
            $itemlevel = 3;
        if ($playerlevel > 45 && $playerlevel <= 70)
            $itemlevel = 4;
        if ($playerlevel > 70 && $playerlevel <= 100)
            $itemlevel = 5;

        if ($itemlevel >= 1 && $itemlevel <= 5) {
            while ($flag) {
                $itemId = rand(1, 30);
                $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
                $item = $itemMeta->itemObject;
                if ($item->level == $itemlevel) {
                    $flag = false;
                }
            }
        } else {
            throw new CException(Yii::t('Login', 'you level config is error'));
        }

        return $itemId;
    }

    public function initRandArr() {
        $randArr = array();
        $rnd = array(1 => 50, 2 => 30, 3 => 15, 4 => 2, 5 => 2, 6 => 0.5, 7 => 0.5);
        foreach ($rnd as $n => $randNum) {
            for ($i = 1; $i <= $randNum * 10; $i++) {
                $randArr[$i] = $n;
            }
        }
        return $randArr;
    }

    public function randDisorder($arr) {
        $arr_size = sizeof($arr);
        $tmp_arr = array();
        for ($i = 0; $i < $arr_size; $i++) {
            mt_srand((double) microtime() * 1000000);
            $rd = mt_rand(0, $arr_size - 1);
            if (isset($tmp_arr[$rd]) && !empty($tmp_arr[$rd])) {
                $tmp_arr[$rd] = $arr[$i];
            } else {
                $i = $i - 1;
            }
        }
        return $tmp_arr;
    }

    public function removeReward() {
        $this->playerLogin->rewardInfo = '';
        $this->playerLogin->saveAttributes(array('rewardInfo'));
    }

    public function addReward($index, $num = 1) {
        $rewardInfo = $this->getRewardInfo();
        if (isset($rewardInfo[$index])) {
            $rewardInfo[$index] = $rewardInfo[$index] + $num;
        } else {
            $rewardInfo[$index] = $num;
        }
        $this->playerLogin->rewardInfo = serialize($rewardInfo);
        $this->playerLogin->saveAttributes(array('rewardInfo'));
        $desc = 'add a reward to playerLogin_Table id is' . $index . ', add num is ' . $num . ', playerId is' . $this->playerId . '.';
        $this->saveLog($index, $num, 'systemGive', $desc, $this->playerId);
    }

    public function useReward($index, $num = 1) {
        $rewardId = $index;
        $rewardInfo = $this->getRewardInfo();
        if (isset($rewardInfo[$rewardId])) {
            if ($rewardInfo[$rewardId] > $num) {
                $rewardInfo[$rewardId] -= $num;
            } elseif ($rewardInfo[$rewardId] == $num) {
                unset($rewardInfo[$rewardId]);
            } else {
                throw new CException(Yii::t('Reward', 'no enough Reward'));
            }
        } else {
            throw new CException(Yii::t('Reward', 'you have no Reward'));
        }
        $this->playerLogin->rewardInfo = serialize($rewardInfo);
        $this->playerLogin->saveAttributes(array('rewardInfo'));
        $desc = 'get a reward from playerLogin_Table id is' . $index . ', add num is ' . $num . ', playerId is' . $this->playerId . '.';
        $this->saveLog($index, $num, 'playerGet', $desc, $this->playerId);
    }

    public function addToItem($index, $addFrom, $num) {
        $itemModel = Yii::app()->objectLoader->load('Item', $this->playerId);
        $item = new ItemMeta($index);
        $itemModel->addItem($item, $addFrom, $num);
    }

    public function addToGold($gold) {
        $this->player->addGold($gold);
    }

    public function addToSeed() {
        $seedModel = Yii::app()->objectLoader->load('SeedModel', $this->playerId);
        $seed = $seedModel->generateSeed(SEED_FROM_LOGINREWARD);
        $mailInfo = array(
            'informType' => MAIL_SYSTEMMAIL,
            'fromId' => MAIL_DEFAULTFROMID,
            'seedId' => $seed->seedId,
            'content' => '[%newSeedMail%]',
        );
        MailModel::givePresent($mailInfo, array($this->playerId));
        return $seed->seedId;
    }

    public function addToMoney($money) {
        $playerMoney = Yii::app()->objectLoader->load('PlayerMoney', $this->playerId);
        $playerMoney->send($money, 'Add Money From LoginReward');
    }

    public function setRewards($index) {
        $seedId = 0;
        $gold = 0;
        $reward = $this->RewardAttributes->getReward($index);
        if ($reward['type'] == 'useItem' || $reward['type'] == 'resItem' || $reward['type'] == 'decoItem') {
            $this->addToItem($reward['id'], 'LoginReward', $reward['num']);
            $desc = 'use reward as useItem itemId is' . $reward['id'] . ', item num is ' . $reward['num'] . ', playerId is' . $this->playerId . '.';
            $this->saveLog($index, 1, 'playerUse', $desc, $this->playerId);
        };
        if ($reward['type'] == 'gold') {
            if (isset($reward['rand']) && !empty($reward['rand'])) {
                $gold = $this->player->level * rand($reward['rand'][0], $reward['rand'][1]);
            }
            if (isset($reward['levelGold']) && !empty($reward['levelGold'])) {
                $gold = $reward['levelGold'] * $this->player->level;
            }
            $this->addToGold($gold);
            $desc = 'use reward as ' . $gold . 'gold, playerId is' . $this->playerId . '.';
            $this->saveLog($index, 1, 'playerUse', $desc, $this->playerId);
        };
        if ($reward['type'] == 'seed') {
            $seedId = $this->addToSeed();
            $desc = 'use reward as seed' . $seedId . ', playerId is' . $this->playerId . '.';
            $this->saveLog($index, 1, 'playerUse', $desc, $this->playerId);
        };
        if ($reward['type'] == 'money') {
            $this->addToMoney($reward['money']);
            $desc = 'use reward as ' . $reward['money'] . 'gold, playerId is' . $this->playerId . '.';
            $this->saveLog($index, 1, 'playerUse', $desc, $this->playerId);
        };

        $params = array(
            'seedId' => $seedId,
            'gold' => $gold,
        );

        return $params;
    }

    public function getRewards() {
        $rewards = array();
        $rewardInfo = $this->getRewardInfo();
        foreach ($rewardInfo as $i => $num) {
            if ($num != 0) {
                $reward = $this->RewardAttributes->getReward($i);
                if ($reward['type'] == 'useItem' || $reward['type'] == 'resItem' || $reward['type'] == 'decoItem') {
                    $itemMeta = new ItemMeta($reward['id']);
                    $rewards[$i] = array('reward' => $reward, 'type' => 'item', 'num' => $num, 'item' => $itemMeta->itemObject);
                };
                if ($reward['type'] == 'gold') {
                    $rewards[$i] = array('reward' => $reward, 'type' => 'gold', 'num' => $num);
                };
                if ($reward['type'] == 'seed') {
                    $rewards[$i] = array('reward' => $reward, 'type' => 'seed', 'num' => $num);
                };
                if ($reward['type'] == 'money') {
                    $rewards[$i] = array('reward' => $reward, 'type' => 'money', 'num' => $num);
                };
            }
        }
        return $rewards;
    }

    public function getRewardsCount() {
        $count = 0;
        $rewardInfo = $this->getRewardInfo();
        foreach ($rewardInfo as $i => $num) {
            $count = $count + $num;
        };
        return $count;
    }

    public function saveLog($id, $num, $actionType, $desc, $playerId = 0) {
        if (empty($playerId)) {
            $playerId = $this->playerId;
        }
        return LoginRewardLog::save($id, $num, $actionType, $desc, $playerId);
    }

}

?>
