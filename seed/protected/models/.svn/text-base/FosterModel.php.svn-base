<?php

class FosterModel extends Model {

    private $playerId;
    private $player;

    public function __construct($playerId) {
        $this->playerId = $playerId;
        $this->player = Yii::app()->objectLoader->load('Player', $this->playerId);
    }

    public function getGardenSign($gardenId) {
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        return $garden->gardenSign;
    }

    public static function getLevelFoster($level) {
        if ($level > 99) {
            $level = 99;
        }
        if ($level < 10) {
            $fosterSeedNum = 1;
        } else {
            $fosterSeedNum = (int) ($level / 10);
        }
        return $fosterSeedNum;
    }

    public function fosterSeed($seedId, $friendId, $gardenSign = 1) {
        //添加到的garden的player是不是当前用户的好友
        //当前的用户是不是种子的所有者
        //种子所在的花园，是不是当前用户的，也就是是不是已经被寄养了
        $seed = Yii::app()->objectLoader->load('Seed', $seedId);
        if ($seed->growPeriod < SEED_GROW_PERIOD_GROWING) {
            throw new CException(Yii::t('Seed', 'you could not foster the seed'));
        }
        $garden = Yii::app()->objectLoader->load('Garden', $seed->gardenId);
        $playerFriend = Yii::app()->objectLoader->load('PlayerFriend', $this->playerId . '_' . $friendId);
        //check point
        $seed->checkOwner($this->playerId);
        $garden->checkOwner($this->playerId);
        $fosterSeedNum = Seed::FosterSeedNum($this->playerId);
        $level = $this->player->level;
        if ($level > 99) {
            $level = 99;
        }
        if ($level < 10) {
            if ($fosterSeedNum >= 1) {
                throw new SException(Yii::t('Seed', 'you level can not foster more seed'));
            }
        } else {
            if ($fosterSeedNum >= (int) ($level / 10)) {
                throw new SException(Yii::t('Seed', 'you level can not foster more seed'));
            }
        }
        if ($seed->isGrown()) {
            throw new SException(Yii::t('Seed', 'you could not foster a grown seed'));
        }
        if ($seed->isFoster == 1) {
            throw new SException(Yii::t('Seed', 'the seed has been fostered'));
        }
        if (PlayerFriend::isFriend($this->playerId, $friendId) != 0) {
            throw new CException(Yii::t('Garden', 'your are not friend,you can not foster a seed'));
        }
        if (!empty($playerFriend->fosterSeed)) {
            throw new SException(Yii::t('Seed', 'you are foster a seed'));
        }
        if ($gardenSign > GARDEN_MAXGARDENSIGN) {
            throw new CException(Yii::t('Garden', 'gardenSign greater than ten'));
        }

        $gardenModel1 = Yii::app()->objectLoader->load('GardenModel', $seed->playerId);
        $gardenModel1->seedRemove($seedId, $seed->gardenId);
        $result = Garden::getSeedListBySign($friendId, $gardenSign);
        $seedCount = $result['seedCount'] + 1;
        if (!empty($result)) {
            if ($result['fosterList'] == '') {
                $positionStr = $result['fosterList'];
                $dom = $positionStr == '' ? '' : ',';
                $positionStr.= $dom . (string) $seedId;
                $positionStr = trim($positionStr, ",");
                if ($positionStr != '') {
                    $list = explode(',', $positionStr);
                    $list = array_unique($list);
                    $positionStr = implode(',', $list);
                }
                $dataUpdate = array(
                    'fosterList' => $positionStr,
                    'seedCount' => $seedCount,
                );
                $updateList = array('fosterList', 'seedCount');
                $gardenModel2 = Yii::app()->objectLoader->load('GardenModel', $friendId);
                $gardenModel2->updateInfo($dataUpdate, $result['gardenId'], $updateList);
                $seedModel = Yii::app()->objectLoader->load('SeedModel', $this->playerId);
                $seedModel->fosterSeed($seedId, $result['gardenId']);
                PlayerFriend::addFosterSeed($friendId, $seedId);
            } else {
                throw new CException(Yii::t('Garden', 'no place can add a fosterSeed in this garden'));
            }
        } else {
            throw new CException(Yii::t('Garden', 'no garden can add a seed in this garden'));
        }
    }

    public function seedToMail($seedArr, $friendId) {
        //添加到的garden的player是不是当前用户的好友
        //当前的用户是不是种子的所有者
        //种子所在的花园，是不是当前用户的，也就是是不是已经被寄养了
        //种子ID记录到邮件中，生成一封邮件，当作邮件发送
        $seedArray = array();
        if (!is_array($seedArr)) {
            $seedArray[0] = $seedArr;
        } else {
            $seedArray = $seedArr;
        }
        foreach ($seedArray as $seedId) {
            $seed = Yii::app()->objectLoader->load('Seed', $seedId);
            $garden = Yii::app()->objectLoader->load('Garden', $seed->gardenId);
            $positionStr = '';
            $fosterList = $garden->fosterList;
            $seedCount = $garden->seedCount - 1;
            if ($fosterList !== '') {
                $array = explode(',', $fosterList);
                foreach ($array as $i => $arr) {
                    if ($arr != $seedId) {
                        $dom = $i == 0 ? '' : ',';
                        $positionStr .= $dom . $array[$i];
                    }
                }
                $positionStr = trim($positionStr, ",");
                $data = array(
                    'fosterList' => $positionStr,
                    'seedCount' => $seedCount,
                );
                $updateList = array('fosterList', 'seedCount');
                $gardenModel = Yii::app()->objectLoader->load('GardenModel', $friendId);
                $gardenModel->updateInfo($data, $seed->gardenId, $updateList);
                $seedModel = Yii::app()->objectLoader->load('SeedModel', $this->playerId);
                $seedModel->fosterSeedBack($seedId);
                $this->createSeedMailInfo($seed, $friendId);
                PlayerFriend::recycleFosterSeed($friendId, $seedId);
            }
        }
    }

    public function createSeedMailInfo($seed, $friendId) {
//        $rowQueue = MailQueue::queueCount($seed->playerId);
//        if ($rowQueue[0] >= MAIL_MAXQUEUE) {
//            if ($seed->playerId == $this->playerId) {
//                throw new SException(Yii::t('Mail', 'you mail queue count is max,you can not foster back you seed'));
//            } else {
//                $player = Yii::app()->objectLoader->load('Player', $seed->playerId);
//                throw new SException($player->playerName . Yii::t('Mail', 'you friend mail queue count is max,you can not foster back the seed'));
//            }
//        }

        $seedMailInfo = array(
            'playerId' => $seed->playerId,
            'isGet' => 0,
            'getDays' => MAIL_DEFAULTVALUE,
            'keepDays' => MAIL_DEFAULTVALUE,
            'informType' => MAIL_PLAYERMAIL,
            'fromId' => $friendId,
            'content' => '',
            'goodsId' => MAIL_DEFAULTVALUE,
            'seedId' => $seed->seedId,
            'sentGold' => MAIL_DEFAULTVALUE,
            'isRead' => 0,
        );

        if ($seedMailInfo['informType'] == MAIL_PLAYERMAIL) {
            $seedMailInfo['keepDays'] = time() + 86400 * MAIL_MAXKEEPDAYS;
        }

        if (isset($seedMailInfo['seedId']) && $seedMailInfo['seedId'] != 0) {
            $seed = Yii::app()->objectLoader->load('Seed', $seedMailInfo['seedId']);
            $seedLevel = $seed->getMyLevel();
            $seedMailInfo['getDays'] = time() + (int) (3600 * ($seedLevel / 4));
        }

        $arrayList = explode(',', $seed->playerId);
        MailModel::sendMails($seedMailInfo, $arrayList, $this->playerId);
    }

}

?>
