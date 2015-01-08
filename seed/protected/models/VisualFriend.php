<?php
/**
 * VisualFriend
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-05-10
 * @package Seed
 **/
class VisualFriend extends ConfigModel
{
    public $id;
    public $playerId;
    public $player;

    function __construct($key)
    {
        $value = explode('_', $key);
        $id = $value[0];
        $playerId = $value[1];
        $this->id = $id;
        $this->playerId = $playerId;
        $this->player = Yii::app()->objectLoader->load('Player', $playerId);
        parent::__construct($this->id);
    }

    public static function createKey($id, $playerId)
    {
        return $id.'_'.$playerId;
    }

    public function isFoster()
    {
        if ($this->player->getStatus('visualFoster')) {
            return true;
        } else {
            return false;
        }
    }

    public function setFosterSeedId($seedId=null)
    {
        return $this->player->setStatus('visualFoster', $seedId);
    }

    public function getFosterSeedId()
    {
        return $this->player->getStatus('visualFoster');
    }

    public function setBreedCDTime($breedCDTime=0, $seedId)
    {
        $timeArray = unserialize($this->player->getStatus('breedCDTime'));
        if (empty($timeArray)) {
            $timeArray = array();
        }
        $timeArray[$seedId] = $breedCDTime;
        return $this->player->setStatus('breedCDTime', serialize($timeArray));
    }

    public function getBreedCDTime($seedId)
    {
        $timeArray = unserialize($this->player->getStatus('breedCDTime'));
        return empty($timeArray[$seedId]) ? 0 : $timeArray[$seedId];
    }

    public function getFosterSeed()
    {
        if ($this->isFoster()) {
            return Yii::app()->objectLoader->load('Seed', $this->player->getStatus('visualFoster'));
        } else {
            return null;
        }
    }

    public function getFavoriteSeed()
    {
        if (!empty($this->favoriteSeed)) {
            return $this->getSeed($this->favoriteSeed);
        } else {
            return null;
        }
    }

    public function fosterSeed($seedId, $gardenId)
    {
        $seed = Yii::app()->objectLoader->load('Seed', $seedId);
        $garden = Yii::app()->objectLoader->load('Garden', $seed->gardenId);
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
        if ($this->isFoster()) {
            throw new SException(Yii::t('Seed', 'you are foster a seed'));
        }
        $fromGarden = Yii::app()->objectLoader->load('GardenModel', $seed->playerId);
        $fromGarden->seedRemove($seedId, $seed->gardenId);
        $this->setFosterSeedId($seedId);
        $seedModel = Yii::app()->objectLoader->load('SeedModel', $this->playerId);
        $seedModel->fosterSeed($seedId, $gardenId);
    }

    //繁殖用
    public function getSeed($seedId)
    {
        $seeds = Util::loadConfig('VisualSeeds');
        if (array_key_exists($seedId, $seeds)) {
             $seed = Yii::app()->objectLoader->load('VisualSeed', $seedId);
             $seed->loadRecord($seeds[$seedId]);
             $seed->breedCDTime = $this->getBreedCDTime($seedId);
             return $seed;
        } else {
            throw new SException(Yii::t('VisualFriend', 'seed does not exist'));
        }
       
    }

    //花园列表用
    public static function getSeedObject($seedId)
    {
        $seeds = Util::loadConfig('VisualSeeds');
        if (array_key_exists($seedId, $seeds)) {
            $seed = Yii::app()->objectLoader->load('VisualSeed', $seedId);
            $seed->loadRecord($seeds[$seedId]);
            return $seed;
        } else {
            throw new SException(Yii::t('VisualFriend', 'seed does not exist'));
        }
    }

    public static function getGarden($gardenId)
    {
        $gardens = Util::loadConfig('VisualGardens');
        if (array_key_exists($gardenId, $gardens)) {
            $garden = Yii::app()->objectLoader->load('VisualGarden', $gardenId);
            $garden->loadRecord($gardens[$gardenId]);
            return $garden;
        } else {
            throw new SException(Yii::t('VisualFriend', 'garden does not exist'));
        }
    }

    public function getGardens()
    {
        $result = array();
        $gardens = Util::loadConfig('VisualGardens');
        
        foreach ($this->gardenList as $gardenId) {
            $garden = Yii::app()->objectLoader->load('VisualGarden', $gardenId);
            $garden->loadRecord($gardens[$gardenId]);
            $result[] = $garden;
        }
        return $result;        
    }
    
    public function saveBreedCDTime($seedId)
    {
        $breedCDTime = SEED_BREED_CD_TIME*60+time() ;
        $this->setBreedCDTime($breedCDTime, $seedId); 
    }

    public static function checkOwnerByGarden($gardenId)
    {
        $visualPlayers = Util::loadConfig('VisualFriend');
        foreach ($visualPlayers as $id=>$visualPlayer) {
            foreach ($visualPlayer['gardenList'] as $key) {
                if ($gardenId==$key) {
                    return $id;
                }
            }
        }
        return ;
    }

    public static function checkOwnerBySeed($seedId)
    {
        $visualPlayers = Util::loadConfig('VisualFriend');
        foreach ($visualPlayers as $id=>$visualPlayer) {
            foreach ($visualPlayer['seedList'] as $key) {
                if ($seedId==$key) {
                    return $id;
                }
            }
        }
        return ;
    }

    public static function checkVisualFriend($playerId)
    {
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        foreach (Util::loadConfig('VisualFriendLevelStage') as $id=>$data) {
            if ($player->level>=$data['show'] and $player->level<$data['hide']) {
                return $id;
            }
        }
        return ;
    }

    public static function getVisualPlayerNameById($id)
    {
        foreach (Util::loadConfig('VisualFriend') as $key=>$data) {
            if ($key==$id) {
                return $data['playerName'];
            }
        } 
        return ;
    }

    public static function isVisualFriend($playerId, $friendId)
    {
        $id = self::checkVisualFriend($playerId);
        return $id == $friendId;
    }

    public static function isChargedToday($playerId)
    {
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        $lastChargeTime = $player->getStatus('chargeCDTime');
        $today = strtotime(date('Y-m-d 00:00:00'));
        
        return $lastChargeTime>$today;
    }

    public static function setChargeCDTime($playerId, $time)
    {
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        return $player->setStatus('chargeCDTime', $time);
    }

    public static function getSupplyPowerRemainTime()
    {
        return 3600*24*365*100;
    }

    public static function checkFosterSeedAsLevelUp($playerId)
    {
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        foreach (Util::loadConfig('VisualFriendLevelStage') as $id=>$data) {
            if ($player->level == $data['hide']) {
                if ($player->getStatus('visualFoster')) {
                    $seedId = $player->getStatus('visualFoster');
                    $seedModel = Yii::app()->objectLoader->load('SeedModel', $playerId);
                    $seedModel->fosterSeedBack($seedId);
                    $seed = Yii::app()->objectLoader->load('Seed', $seedId);
                    //通过系统邮箱打回种子
                    $seedMailInfo = array(
                        'playerId' => $seed->playerId,
                        'isGet' => 0,
                        'getDays' => MAIL_DEFAULTVALUE,
                        'keepDays' => MAIL_DEFAULTVALUE,
                        'informType' => MAIL_SYSTEMMAIL,
                        'fromId' => MAIL_DEFAULTFROMID,
                        'content' => '[%VisualMailContent%]_'.$id,
                        'goodsId' => MAIL_DEFAULTVALUE,
                        'seedId' => $seed->seedId,
                        'sentGold' => MAIL_DEFAULTVALUE,
                        'isRead' => 0,
                    ); 
                    if (isset($seedMailInfo['seedId']) && $seedMailInfo['seedId'] != 0) {
                        $seed = Yii::app()->objectLoader->load('Seed', $seedMailInfo['seedId']);
                        $seedLevel = $seed->getMyLevel();
                        $seedMailInfo['getDays'] = time() + (int) (3600 * ($seedLevel / 4));
                    }

                    $arrayList = explode(',', $seed->playerId);
                    MailModel::sendMails($seedMailInfo, $arrayList);

                    $key = VisualFriend::createKey($id, $player->playerId);
                    $visualFriend = Yii::app()->objectLoader->load('VisualFriend', $key);
                    $visualFriend->setFosterSeedId();
                }
                return ;
            }
        }
        return ;
    }
}
?>
