<?php

class SeedUser extends RecordModel {

    public $userId;

    public function __construct($userId) {
        $this->userId = $userId;
    }

    public static function attributeColumns() {
        return array(
            'userId', 'lastLoginWorldId', 'lastLoginTime', 'sessionId', 'createTime'
        );
    }

    protected function loadData() {
        $command = Yii::app()->db->createCommand('SELECT * FROM seedUser WHERE userId=:userId');
        $rowData = $command->bindParam(':userId', $this->userId)->queryRow();
        return $rowData;
    }

    protected function saveData($attributes = array()) {
        return DbUtil::update(Yii::app()->db, 'seedUser', $attributes, array('userId' => $this->userId));
    }

    public static function multiLoad($params = array(), $isSimple = true) {
        $sql = "SELECT * FROM seedUser";
        if (!empty($params)) {
            $sql .= ' WHERE ' . implode(' AND ', $params);
        }
        return self::multiLoadBySql($sql, 'userId', array(), $isSimple);
    }

    public static function create($createInfo) {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();
        return DbUtil::insert(Yii::app()->db, 'seedUser', $insertArr, true);
    }

//    public function login($worldId) {
//        $lastLoginTime = $this->lastLoginTime;
//        $this->lastLoginWorldId = $worldId;
//        $currentTime = time();
//        $this->lastLoginTime = $currentTime;
//        $this->save();
//
//        if (!isset($lastLoginTime) or (Util::getDayTime($currentTime) > Util::getDayTime($lastLoginTime))) {
//            //当日第一次登陆 1)当日活跃用户数+1
//            $dayTime = Util::getDayTime($currentTime);
//            $userLoginSummaryModel = new UserLoginSummary();
//            $userLoginSummary = $userLoginSummaryModel->findByPk($dayTime);
//            if (!isset($userLoginSummary)) {
//                $userLoginSummaryModel->dayTime = $dayTime;
//                $userLoginSummaryModel->save();
//            }
//            $userLoginSummaryModel->updateCounters(array('activeUserNum' => 1), 'dayTime = :dayTime', array(':dayTime' => $dayTime));
//        }
//        if (!isset($lastLoginTime) or (Util::getMonthTime($currentTime) > Util::getMonthTime($lastLoginTime))) {
//            //当月第一次登陆 1)当月活跃用户数+1
//            $monthTime = Util::getMonthTime($currentTime);
//            $userLoginSummary_MModel = new UserLoginSummary_M();
//            $userLoginSummary_M = $userLoginSummary_MModel->findByPk($monthTime);
//            if (!isset($userLoginSummary_M)) {
//                $userLoginSummary_MModel->monthTime = $monthTime;
//                $userLoginSummary_MModel->save();
//            }
//            $userLoginSummary_MModel->updateCounters(array('activeUserNum' => 1), 'monthTime = :monthTime', array(':monthTime' => $monthTime));
//        }
//    }
//    public function getLastLoginWorld() {
//        if (isset($this->lastLoginWorldId)) {
//            $worlds = new SevenWorlds();
//            try {
//                $world = $worlds->getWorld($this->lastLoginWorldId);
//
//                //To be compatible with legacy data, check if user has joined this world.
//                $worldInfo = $world->getWorldInfo($this->userId);
//                if ($worldInfo['status']['hasJoined']) {
//                    return $world;
//                } else {
//                    return null;
//                }
//            } catch (SException $e) {
//                Yii::log("Failed to get world $this->lastLoginWorldId:" . $e->getMessage(), CLogger::LEVEL_WARNING, 'application');
//                return null;
//            }
//        } else {
//            return null;
//        }
//    }
}
