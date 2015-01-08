<?php

class ActiveUserReportForm extends CFormModel {

    public $searchRange = 0;
    public $resultTimeType = 0;
    public $startDate;
    public $endDate;

    public function rules() {
        return array(
            array('searchRange, resultTimeType, startDate, endDate', 'required'),
            array('searchRange', 'checkRange'),
            array('endDate', 'checkendDate'),
            array('startDate', 'checkstartDate'),
            array('searchWorldId', 'checkWorldId'),
            array('resultTimeType', 'checktimeType'),
        );
    }

    public function init() {
        $this->startDate = self::makeDayStr() . '/01';
        $this->endDate = self::makeDayStr2();
    }

    public function checkRange() {
        if (!$this->hasErrors()) {
            if ($this->searchRange != 0 && $this->searchRange != 1 && $this->searchRange != 2) {
                $this->addError('searchRange', 'Not in Range');
            }
        }
    }

    public function checktimeType() {
        if (!$this->hasErrors()) {
            if ($this->resultTimeType != 0 && $this->resultTimeType != 1 && $this->resultTimeType != 2) {
                $this->addError('resultTimeType', 'Incorrect TimeType');
            }
        }
    }

    public function checkendDate() {
        if (!$this->hasErrors()) {
            $parts = explode('/', $this->endDate);
            $year = $parts[0];
            $month = $parts[1];
            $day = $parts[2];
            if (!checkdate($month, $day, $year)) {
                $this->addError('endDate', 'The format of endDate is error');
            }
        }
    }

    public function checkstartDate() {
        if (!$this->hasErrors()) {
            $parts = explode('/', $this->startDate);
            $year = $parts[0];
            $month = $parts[1];
            $day = $parts[2];
            if (!checkdate($month, $day, $year)) {
                $this->addError('startdDate', 'The format of startDate is error');
            } else {
                $time = time();
                if (strtotime($this->startDate) > $time) {
                    $this->addError('startDate', 'The startDate should prior to current time');
                } else if (strtotime($this->startDate) > strtotime($this->endDate)) {
                    $this->addError('startDate', 'The startDate should prior to the endDate');
                }
            }
        }
    }

    public function checkWorldId($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->searchRange == 2) {
                if (!preg_match("/^\d+$/", $this->$attribute)) {
                    $this->addError($attribute, $attribute . ': should be integer!');
                }
            }
        }
    }

    public function reportResult() {
        $result = array();
        $timeList = array();
        $startDayTime = Util::getDayTime(self::parseDayStr($this->startDate));
        $endDayTime = Util::getDayTime(self::parseDayStr($this->endDate));
        if ($this->resultTimeType == 0) {//day
            $tmpTime = $startDayTime;
            while ($tmpTime <= $endDayTime) {
                $timeList[] = $tmpTime;
                $tmpTime += 86400;
            }
            $record = Yii::app()->db->createCommand("SELECT * FROM playerLoginSummary WHERE dayTime >= :startTime AND dayTime <= :endTime")->queryAll(true, array(':startTime' => $startDayTime, ':endTime' => $endDayTime));
            $result['app'] = Util::changeArrayToHashByKey($record, 'dayTime');
        } else if ($this->resultTimeType == 1) {//month
            $startMonthTime = Util::getMonthTime($startDayTime);
            $endMonthTime = Util::getMonthTime($endDayTime);

            $tmpTime = $startMonthTime;
            while ($tmpTime <= $endMonthTime) {
                $timeList[] = $tmpTime;
                $tmpTime = strtotime("+1 months", $tmpTime);
            }
            $record = Yii::app()->db->createCommand("SELECT * FROM playerLoginSummary_M WHERE monthTime >= :startTime AND monthTime <= :endTime")->queryAll(true, array(':startTime' => $startMonthTime, ':endTime' => $endMonthTime));
            $result['app'] = Util::changeArrayToHashByKey($record, 'monthTime');
        }

        $activeTimeList = array();
        foreach ($result as $title => $line) {
            foreach ($line as $timeTitle => $lineData) {
                $activeTimeList[$timeTitle] = 1;
            }
        }
        foreach ($timeList as $index => $tmpTime) {
            if (isset($activeTimeList[$tmpTime]) and ($activeTimeList[$tmpTime] == 1)) {
                //do nothing
            } else {
                unset($timeList[$index]);
            }
        }

        return array('result' => $result, 'timeList' => $timeList);
    }

    public static function makeDayStr($timestamp = null) {
        if (!isset($timestamp)) {
            $timestamp = time();
        }
        return date('Y/m', $timestamp);
    }

    public static function makeDayStr2($timestamp = null) {
        if (!isset($timestamp)) {
            $timestamp = time();
        }
        return date('Y/m/d', $timestamp);
    }

    public static function makeMonthStr($timestamp = null) {
        if (!isset($timestamp)) {
            $timestamp = time();
        }
        return date('Y/m', $timestamp);
    }

    public static function parseDayStr($dayStr) {
        if (preg_match("/^(\d*)\/(\d*)\/(\d*)$/", $dayStr, $matchs)) {
            return mktime(0, 0, 0, intval($matchs[2]), intval($matchs[3]), intval($matchs[1]));
        }
    }

}