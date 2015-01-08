<?php

class LoginRewardLog {

    public static $actionTypes = array(
        'systemGive', 'playerGet', 'playerUse',
    );

    public static function save($rewardId, $num, $actionType, $desc, $playerId = 0) {
        if (!in_array($actionType, self::$actionTypes)) {
            return;
            //throw new CException('Action type not defined.');
        }
        $array = array(
            'rewardId' => $rewardId,
            'num' => $num,
            'actionType' => $actionType,
            'desc' => $desc,
            'playerId' => $playerId,
            'createTime' => time(),
        );
        return DbUtil::insert(Yii::app()->db, 'loginRewardLog', $array);
    }

}
