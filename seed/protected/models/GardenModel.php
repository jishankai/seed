<?php

class GardenModel extends Model {

    private $playerId;
    private $player;

    public function __construct($playerId) {
        $this->playerId = $playerId;
        $this->player = Yii::app()->objectLoader->load('Player', $this->playerId);
        $this->attachBehavior('GardenAttributes', new GardenAttributes);
    }

    public static function getGardenSignT($gardenId) {
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        return $garden->gardenSign;
    }

    public function getGardenSign($gardenId) {
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        //check point
        $garden->checkOwner($this->playerId);
        return $garden->gardenSign;
    }

    public static function maxSeedGardenCount($playerId) {
        return Garden::maxSeedGardenCount($playerId);
    }

    public static function isBackgroundGetEightType($playerId) {
        $groundCount = 0;
        $result = Garden::getBackgroundByPlayerId($playerId);
        $array = array();
        if (!empty($result)) {
            foreach ($result as $value) {
                array_push($array, $value['backGround']);
            }
            $array = array_unique($array);
            $groundCount = count($array);
            if ($groundCount >= GARDEN_BACKGROUNDVALIDATE) {
                return true;
            }
        }
        return false;
    }

    public function getGardenDecoShow($gardenId) {
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        //check point
        $garden->checkOwner($this->playerId);
        return $garden->getGardenDecoShow();
    }

    /*
     * 获得所有花园的信息
     */

    public function getGardenInfoAll() {
        $params = array();
        $dataArray = array();
        $param = "playerId = " . $this->playerId;
        array_push($params, $param);
        $dataArray = Garden::multiLoad($params, true);
        return $dataArray;
    }

    /*
     * 获得所有花园的信息Object方式，可获得对象的方法
     */

    public function getGardenObject() {
        $params = array();
        $dataArray = array();
        $param = "playerId = " . $this->playerId;
        array_push($params, $param);
        $dataArray = Garden::multiLoad($params, false);
        return $dataArray;
    }

    /*
     * 向花园中加种子
     */

//    public function getBabyGardenSign()
//    {
//        
//    }

    public static function getSpareGardenSign($isBaby, $playerId) {
        if ($isBaby == true) {
            $spareBabyGarden = Garden::getSpareBabyGarden($playerId);
            if (!empty($spareBabyGarden)) {
                return $spareBabyGarden[0]['gardenSign'];
            } else {
                throw new SException(Yii::t('Garden', 'no1 garden can not add a seed'));
            }
        } else {
            $spareGrownGarden = Garden::getSpareGrownGarden($playerId);
            if (!empty($spareGrownGarden)) {
                return $spareGrownGarden[0]['gardenSign'];
            } else {
                $spareBabyGarden = Garden::getSpareBabyGarden($playerId);
                if (!empty($spareBabyGarden)) {
                    return $spareBabyGarden[0]['gardenSign'];
                } else {
                    throw new SException(Yii::t('Garden', 'no garden can add a seed'));
                }
            }
        }
    }

    public function addSeedToGarden($seedId, $gardenSign = 1) {
        $lock = new EventLock;
        $lockKey = 'gardenId';
        if ($lock->getLock('garden', $lockKey)) {
            try {
                $seed = Yii::app()->objectLoader->load('Seed', $seedId);
                //check point
                $seed->checkOwner($this->playerId);

                if ($gardenSign != 1 && $seed->growPeriod < SEED_GROW_PERIOD_GROWING) {
                    throw new SException(Yii::t('Garden', 'you seed can not add to this garden, growPercent is not enough'));
                }

                if ($gardenSign > GARDEN_MAXGARDENSIGN)
                    throw new CException(Yii::t('Garden', 'gardenSign greater than ten'));
                $result = Garden::getSeedListBySign($this->playerId, $gardenSign);
                if (!empty($result)) {
                    $garden = Yii::app()->objectLoader->load('Garden', $result['gardenId']);
                    $array = explode(',', $garden->seedList);
                    if (sizeof($array) < GARDEN_OWNERMAXSEED) {
                        array_push($array, $seedId);
                        $array = array_unique($array);
                        $seedList = implode(',', $array);
                        $seedList = trim($seedList, ',');
                        $dataUpdate = array(
                            'seedList' => $seedList,
                            'seedCount' => sizeof($array),
                        );
                        $updateList = array('seedList', 'seedCount');
                        $this->updateInfo($dataUpdate, $garden->gardenId, $updateList);
                        $seed->setGrowValue();
                        $seed->updateGarden($garden->gardenId);

                        //齐聚一堂、物种大师和克隆成就检查
                        $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_SEEDINGARDEN);
                        $achieveEvent->onAchieveComplete();
                        //种子放置任务检查
                        $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_SEEDPLANT, array('gardenId' => $garden->gardenId));
                        $missionEvent->onMissionComplete();
                        //种子收集任务检查
                        $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_SEEDBUD);
                        $missionEvent->onMissionComplete();
                        $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_SEEDBODY);
                        $missionEvent->onMissionComplete();

                        $lock->unlock('garden', $lockKey);
                    } else {
                        throw new SException(Yii::t('Garden', 'no place can add a seed in this garden'));
                    }
                } else {
                    throw new CException(Yii::t('Garden', 'no garden can add a seed in this garden'));
                }
            } catch (Exception $ex) {
                $lock->unlock('garden', $lockKey);
                throw $ex;
            }
        } else {
            throw new CException(Yii::t('Garden', 'once click locked'));
        }
    }

    /*
     * 某一花园种子剩余占位
     */

    public function surplusPosition($gardenSign = 1) {
        if ($gardenSign > GARDEN_MAXGARDENSIGN)
            throw new CException(Yii::t('Garden', 'gardenSign greater than ten'));
        $result = Garden::getSeedListBySign($this->playerId, $gardenSign);
        $num = GARDEN_MAXPOSITION;
        if (!empty($result)) {
            $arr = explode(',', $result['seedList']);
            $num = GARDEN_MAXPOSITION - sizeof($arr);
        }
        return $num;
    }

    /*
     * 获得种子的统计数,用于加载时候，统计重新计算
     */

    public static function getSeedCount($seedList, $fosterList) {
        $array1 = array();
        $array2 = array();
        $count1 = 0;
        $count2 = 0;
        if (trim($seedList) != "") {
            $array1 = explode(',', $seedList);
        }
        if (!empty($array1))
            $count1 = sizeof($array1);
        if (trim($fosterList) != "") {
            $array2 = explode(',', $fosterList);
        }
        if (!empty($array2))
            $count2 = sizeof($array2);
        return $count1 + $count2;
    }

    public static function getSeedCountByGardenId($gardenId) {
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        return self::getSeedCount($garden->seedList, $garden->fosterList);
    }

    /*
     * 设置种子的统计数
     */

    public function setSeedCount($dataArr, $gardenId) {
        $this->updateInfo($dataArr, $gardenId, array('seedCount'));
    }

    /*
     * 种子移动
     */

    public function seedMove($seedId, $fromGardenId, $toGardenId, $position = 1) {
        $seed = Yii::app()->objectLoader->load('Seed', $seedId);
        $fromGarden = Yii::app()->objectLoader->load('Garden', $fromGardenId);
        $garden = Yii::app()->objectLoader->load('Garden', $toGardenId);

        //check point
        $seed->checkOwner($this->playerId);
        $fromGarden->checkOwner($this->playerId);
        $garden->checkOwner($this->playerId);

        if ($fromGarden->gardenSign == 1 && $seed->growPeriod < SEED_GROW_PERIOD_GROWING) {
            throw new SException(Yii::t('Garden', 'you seed can not add to this garden, growPercent is not enough'));
        }
        $result = $garden->seedList;
        $array = explode(',', $result);
        if (sizeof($array) < GARDEN_OWNERMAXSEED) {
            $this->seedRemove($seedId, $fromGardenId);
            array_push($array, $seedId);
            $array = array_unique($array);
            $seedList = implode(',', $array);
            $seedList = trim($seedList, ",");
            $dataUpdate = array(
                'seedList' => $seedList,
                'seedCount' => sizeof($array),
            );
            $updateList = array('seedList', 'seedCount');
            $this->updateInfo($dataUpdate, $toGardenId, $updateList);
            $seed->setGrowValue();
            $seed->updateGarden($toGardenId);

            //齐聚一堂、物种大师和克隆成就检查
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_SEEDINGARDEN);
            $achieveEvent->onAchieveComplete();
            //种子放置任务检查
            $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_SEEDPLANT, array('gardenId' => $toGardenId));
            $missionEvent->onMissionComplete();
        } else {
            throw new SException(Yii::t('Garden', 'no place can add a seed in this garden'), EXCEPTION_TYPE_NOTICE);
        }
    }

    /*
     * 从花园中删除种子
     */

    public function seedRemove($seedId, $fromGardenId) {
        $garden = Yii::app()->objectLoader->load('Garden', $fromGardenId);
        //check point
        $garden->checkOwner($this->playerId);

        $result = $garden->seedList;
        if ($result !== false) {
            $array = explode(',', $result);
            foreach ($array as $index => $arr) {
                if ($arr == $seedId) {
                    unset($array[$index]);
                }
            }
            $array = array_unique($array);
            $seedList = implode(',', $array);
            $seedList = trim($seedList, ',');
            $data = array(
                'seedList' => $seedList,
                'seedCount' => sizeof($array),
            );
            $updateList = array('seedList', 'seedCount');
            $this->updateInfo($data, $fromGardenId, $updateList);
        }
    }

    /*
     * 设置最喜爱的花园
     */

    public function setFavorteGarden($gardenId) {
        $garden_new = Yii::app()->objectLoader->load('Garden', $gardenId);
        //check point
        $garden_new->checkOwner($this->playerId);

        $favouriteGarden = $this->player->favouriteGarden;
        if (empty($favouriteGarden)) {
            $garden_new->setFavouriteGardenId(1);
        } else {
            if ($favouriteGarden != $gardenId) {
                $garden_old = Yii::app()->objectLoader->load('Garden', $favouriteGarden);
                $garden_old->setFavouriteGardenId(0);
                $garden_new->setFavouriteGardenId(1);
                $this->player->setFavouriteGarden($gardenId);
            }
        }
    }

    public function setBackGround($gardenId, $groundId) {
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        //check point
        $item = Yii::app()->objectLoader->load('Item', $this->playerId);
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $groundId);
        $item->useItem($itemMeta, 'use background');
        $garden->checkOwner($this->playerId);
        $garden->backGround = $groundId;
        $special = $garden->getSpecial($groundId);
        $decoration1 = Yii::app()->objectLoader->load('DecoItem', $special['decoId1']);
        $decoration1->special = 1;
        $garden->addNewSpecial($decoration1);
        $decoration2 = Yii::app()->objectLoader->load('DecoItem', $special['decoId2']);
        $decoration2->special = 2;
        $garden->addNewSpecial($decoration2);
        $garden->saveAttributes(array('backGround'));
    }

    /*
     * 计算购买花园的金钱
     */

    public function useGardenPrice() {
        $data = array();
        $gardenNum = $this->player->gardenNum;
        if (!empty($gardenNum)) {
            if ($gardenNum < GARDEN_MAXGARDENSIGN) {
                $level = $this->player->level;
                $gold = $this->player->gold;
                $data['gardenCount'] = $gardenNum;
                $price = $this->GardenAttributes->getGardenPrice($data['gardenCount']);
                $data['price'] = $price['price'];
                if ($level < $price['level']) {
                    throw new CException(Yii::t('Garden', 'you level can not buy this garden'));
                }
                if ($gold < $price['gold']) {
                    //throw new SException(Yii::t('Garden', 'you gold can not buy this garden'));
                    throw new SException('', EXCEPTION_TYPE_GOLD_NOT_ENOUGH);
                }
                return $data;
            } else {
                throw new CException(Yii::t('Garden', 'you have ten garden,you can not buy more garden'));
            }
        }
    }

    public function getGardenPrice() {
        $data = array();
        $gardenNum = $this->player->gardenNum;
        $level = $this->player->level;
        $gold = $this->player->gold;
        $data['gardenCount'] = $gardenNum;
        if ($data['gardenCount'] < GARDEN_MAXGARDENSIGN) {
            $price = $this->GardenAttributes->getGardenPrice($data['gardenCount']);
            $data['price'] = $price['price'];
            $data['nextlevel'] = $price['level'];
            $data['gold'] = $gold;
            $data['nowlevel'] = $level;
        }
        return $data;
    }

    /*
     * 购买新的花园
     */

    public function getNewGarden() {
        $lock = new EventLock;
        $lockKey = 'gardenId';
        if ($lock->getLock('getNewGarden', $lockKey)) {
            try {
                $gardenNum = $this->player->gardenNum;
                if (empty($gardenNum)) {
                    $gardenSign = 1;
                } else {
                    $gardenSign = $gardenNum + 1;
                }
                $data = array(
                    'playerId' => $this->playerId,
                    'backGround' => GARDEN_DEFAULTBACKGROUND,
                    'seedList' => '',
                    'entrustList' => '',
                    'seedCount' => 0,
                    'decoExtraGrow' => GARDEN_DEFAULTGROW,
                    'decorationInfo' => '',
                    'favouriteFlag' => 0,
                    'gardenSign' => $gardenSign,
                );
                $price = $this->GardenAttributes->getGardenPrice($this->player->gardenNum);
                $this->player->subGold($price['gold']);
                $sequence = Garden::create($data);
                $garden = Yii::app()->objectLoader->load('Garden', $sequence);
                $garden->initGardenDeco();
                $supplyPower = $this->player->getPlayerPoint('supplyPower');
                $supplyPower->addChangeValue(SUPPLYPOWER_CHANGEVALUE_INCREASE);
                $this->player->addGardenNum();
                //全局消息
                GlobalMessage::setPowerWarning($this->playerId);
                //成就检查 
                $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_GARDENNEW);
                $achieveEvent->onAchieveComplete();
                //任务检查
                $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_GARDENBUY);
                $missionEvent->onMissionComplete();
                return $sequence;
            } catch (Exception $ex) {
                $lock->unlock('getNewGarden', $lockKey);
                throw $ex;
            }
        } else {
            throw new CException(Yii::t('Garden', 'once click locked'));
        }
    }

    public static function initGarden($playerId) {
        $data = array(
            'playerId' => $playerId,
            'backGround' => GARDEN_FIRSTBACKGROUND,
            'seedList' => '',
            'entrustList' => '',
            'seedCount' => 0,
            'decoExtraGrow' => GARDEN_DEFAULTGROW,
            'decorationInfo' => '',
            'favouriteFlag' => 1,
            'gardenSign' => 1,
        );
        $sequence = Garden::create($data);
        $garden = Yii::app()->objectLoader->load('Garden', $sequence);
        $garden->initGardenDeco();
        return $sequence;
    }

    /*
     * 获得缓存信息
     */

    public function getInfoFromCache() {
        //        /**  自定义一个缓存的KEY值 * */
        //        $key = 'gardenInfo' . $this->gardenid;
        //        /** 获得缓存值 * */
        //        $cacheData = $this->cache($key);
        //        if (empty($cacheData)) {
        //            $cacheData = $this->getInfo();
        //            /** 设置缓存 * */
        //            $this->cache($key, $cacheData);
        //        }
        //        /** 返回数据 * */
        //        return $cacheData;
    }

    /*
     * 更新花园的信息
     */

    public function updateInfo($dataArray, $gardenId, $updateList) {
        $updateAttributes = array();
        /** 处理需要保存的字段 * */
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        foreach ($updateList as $key) {
            if (isset($dataArray[$key])) {
                /** 字段赋值 * */
                $garden->$key = $dataArray[$key];
                /** 放入保存列表 * */
                $updateAttributes[] = $key;
            }
        }
        /** 保存属性值 * */
        $garden->saveAttributes($updateAttributes);
    }

    /*
     * 获得花园的信息
     */

    public function getGradenInfo($gardenId) {
        $data = array();
        $garden = Yii::app()->objectLoader->load('Garden', $gardenId);
        foreach ($garden->attributeNames() as $key) {
            $data[$key] = $garden->$key;
        }
        return $data;
    }

    public function getAllCupInfo() {
        $cupInfo = array();
        //获取当前用户所有的花园Id
        $allGardenId = Garden::getAllGardenId($this->playerId);
        foreach ($allGardenId as $gardenId) {
            $garden = Yii::app()->objectLoader->load('Garden', $gardenId['gardenId']);
            //单个花园的奖杯信息
            $a1 = $garden->getCupInfo();
            if (!empty($a1)) {
                $cupInfo = empty($cupInfo) ? $a1 : array_merge($cupInfo, $a1);
            }
        }

        //统计累加多个花园的奖杯信息
        return $cupInfo;
    }

    public function updateNewCupInfo($newCupId) {
        $haveCup = -1;
        //获取所有花园的奖杯信息
        $upgradecup = '';
        $allCupInfo = $this->getAllCupInfo();
        if (empty($allCupInfo)) {
            return $haveCup;
        }
        $decoItem = Yii::app()->objectLoader->load('DecoItem', $newCupId);
        //获得配置信息该小类型的所有奖杯
        $cupId = $this->getCupIdByType($decoItem->type);

        foreach ($cupId as $i => $arr) {
            //查询级别比当前传入的cupId的级别小的奖杯
            if ($i <= $newCupId) {
                //转意，是否存在所有当前读出的花园的奖杯信息里
                if (isset($allCupInfo['\'' . $i . '\''])) {
                    //如果存在执行更新
                    $garden = Yii::app()->objectLoader->load('Garden', $allCupInfo['\'' . $i . '\'']);
                    $decoItem = Yii::app()->objectLoader->load('DecoItem', $i);
                    $garden->updateCupInfo($i, $newCupId);
                    $item = Yii::app()->objectLoader->load('Item', $this->playerId);
                    $item->changeCupState($i, 3);
                    $upgradecup = str_replace(substr($decoItem->image, -4), '', $decoItem->image);
                    $haveCup = 1;
                    GlobalState::set($this->playerId, 'REFRESH_CUP', $upgradecup);
                    break;
                }
            }
        }
        return $haveCup;
    }

    public function changeCupInfo($newCupId) {
        //获得item操作类
        $item = Yii::app()->objectLoader->load('Item', $this->playerId);
        //根据cupId获得cup对象
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $newCupId);
        //根据cup对象获得cup小类型
        $cupId = $this->getCupIdByType($itemMeta->getType());
        //循环遍历
        foreach ($cupId as $i => $arr) {
            //如果奖杯Id小于当前的传入奖杯Id
            if ($i < $newCupId) {
                //查询小的奖杯Id在道具表中是否存在
                $itemNum = $item->getItemNum(array($i));
                //如果存在
                if ($itemNum >= 0) {
                    //通过useItem方法去除原来的奖杯
                    $itemMeta2 = Yii::app()->objectLoader->load('ItemMeta', $i);
                    $item->useItem($itemMeta2, 'use cup', $itemNum, true);
                    //3的状态表示删除
                    $item->changeCupState($i, 3);
                    $item->changeCupState($newCupId, 0);
                }
            }
        }
        //同时试图更新
        $updateNewCupInfo = $this->updateNewCupInfo($newCupId);

        if ($updateNewCupInfo == -1) {
            //添加新奖杯到道具表中
            $item->addItem($itemMeta);
            $item->changeCupState($newCupId, 1);
        } else {
            $item->addItem($itemMeta, 'add cup', 0);
        }
        //如果是金奖杯
        if ($newCupId % 3 == 0) {
            //任务检查
            $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_CUP);
            $missionEvent->onMissionComplete();
        }
    }

}

?>
