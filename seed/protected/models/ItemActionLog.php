<?php

class ItemActionLog {

    public static $actionTypes = array(
        'use', 'add',
    );

    public static function save($itemId, $num, $actionType, $desc, $playerId = 0) {
        if (!in_array($actionType, self::$actionTypes)) {
            return;
            //throw new CException('Action type not defined.');
        }
        $array = array(
            'itemId' => $itemId,
            'num' => $num,
            'actionType' => $actionType,
            'desc' => $desc,
            'playerId' => $playerId,
            'createTime' => time(),
        );
        return DbUtil::insert(Yii::app()->db, 'itemActionLog', $array);
    }

}
