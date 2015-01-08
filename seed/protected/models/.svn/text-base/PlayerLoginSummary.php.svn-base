<?php

class PlayerLoginSummary {

    public static $summaryTypes = array(
        'day', 'month',
    );

    public static function save($summaryTypes, $time) {
        if (!in_array($summaryTypes, self::$summaryTypes)) {
            return;
            //throw new CException('Action type not defined.');
        }
        if ($summaryTypes == 'day') {
            $array = array(
                'dayTime' => $time,
                'activePlayerNum' => 1,
                'createTime' => time(),
            );
            if (self::updateLoginSummary($time) == 1) {
                return 1;
            }
            return DbUtil::insert(Yii::app()->db, 'playerLoginSummary', $array);
        }
        if ($summaryTypes == 'month') {
            $array = array(
                'monthTime' => $time,
                'activePlayerNum' => 1,
                'createTime' => time(),
            );
            if (self::updateLoginSummary_M($time) == 1) {
                return 1;
            }
            return DbUtil::insert(Yii::app()->db, 'playerLoginSummary_M', $array);
        }
    }

    public static function updateLoginSummary($dayTime) {
        $command = Yii::app()->db->createCommand("UPDATE playerLoginSummary SET activePlayerNum = activePlayerNum + 1 WHERE dayTime = :dayTime");
        $rowData = $command->execute(array(':dayTime' => $dayTime));
        return $rowData;
    }

    public static function updateLoginSummary_M($monthTime) {
        $command = Yii::app()->db->createCommand("UPDATE playerLoginSummary_M SET activePlayerNum = activePlayerNum + 1 WHERE monthTime = :monthTime");
        $rowData = $command->execute(array(':monthTime' => $monthTime));
        return $rowData;
    }

}

?>