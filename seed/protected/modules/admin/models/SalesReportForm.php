<?php

class SalesReportForm extends CFormModel {

    public $searchUserRange = 0;
    public $searchUserId;
    public $resultTimeType = 1;
    public $startDate;
    public $endDate;

    public function rules() {
        return array(
            array('searchUserRange, resultTimeType, startDate, endDate', 'required'),
            array('searchUserRange', 'checkUserRange'),
            array('searchUserId', 'checkUserId'),
            array('resultTimeType', 'checktimeType'),
            array('endDate', 'checkendDate'),
            array('startDate', 'checkstartDate'),
        );
    }

    public function init() {
        $this->startDate = self::makeDayStr() . '/01';
        $this->endDate = self::makeDayStr2();
    }

    public function checkUserRange() {          //checkUserRange
        if (!$this->hasErrors()) {
            if ($this->searchUserRange != 0 && $this->searchUserRange != 1) {
                $this->addError('searchUserRange', 'Not in UserRange');
            }
        }
    }

    public function checkUserId($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->searchUserRange == 1) {
                if (isset($this->searchUserId) and preg_match("/^\d+$/", $this->searchUserId)) {
                    /*
                	$user = new UserUID();
                    $user = $user->find('userId = :userId', array(':userId' => $this->searchUserId));
                    if (!isset($user)) {
                        $this->addError('searchUserId', 'userId is not exists');
                    }
                    */
                } else {
                    $this->addError('searchUserId', 'Incorrect searchUserId');
                }
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

    public function reportResult() {
        $startTime = self::parseDayStr($this->startDate);
        $endTime = self::parseDayStr($this->endDate) + 86400;
        //$paymentModel = new ItunesPaymentTransaction();
        $selectParam = array('sum(price) AS money', 'count(*) AS num', 'sum(purchaseGold) AS purchaseGold', 'sum(systemGold) AS systemGold');
        $whereParam = array('record_time >= ?', 'record_time < ?');
        $bindValues = array(1 => $startTime, 2 => $endTime);
        $groupParam = array();
        $orderParam = array();

        if ($this->searchUserRange == 1) {
            $whereParam[] = 'userId = ?';
            $bindValues[] = $this->searchUserId;
        }
        if ($this->resultTimeType == 1) {//日别
            $selectParam[] = 'FROM_UNIXTIME(record_time, "%Y/%m/%d") AS timeStr';
            $groupParam[] = 'timeStr';
            $orderParam[] = 'timeStr ASC';
        } else if ($this->resultTimeType == 2) {//月别
            $selectParam[] = 'FROM_UNIXTIME(record_time, "%Y/%m") AS timeStr';
            $groupParam[] = 'timeStr';
            $orderParam[] = 'timeStr ASC';
        }

        $sql = 'SELECT ' . implode(',', $selectParam) . ' FROM ItunesPaymentTransaction WHERE ' . implode(' AND ', $whereParam);
        if (count($groupParam) > 0) {
            $sql .= ' GROUP BY ' . implode(',', $groupParam);
        }
        if (count($orderParam) > 0) {
            $sql .= ' ORDER BY ' . implode(',', $orderParam);
        }
        $command = Yii::app()->db->createCommand($sql);
        if (count($bindValues) > 0) {
            $command->bindValues($bindValues);
        }
        return $command->queryAll();
    }

    public function totalResult() {
        if ($this->resultTimeType != 0) {
            $startTime = self::parseDayStr($this->startDate);
            $endTime = self::parseDayStr($this->endDate) + 86400;
            //$paymentModel = new ItunesPaymentTransaction();
            $selectParam = array('sum(price) AS money', 'count(*) AS num', 'sum(purchaseGold) AS purchaseGold', 'sum(systemGold) AS systemGold');
            $whereParam = array('record_time >= ?', 'record_time < ?');
            $bindValues = array(1 => $startTime, 2 => $endTime);
            $groupParam = array();
            $orderParam = array();

            if ($this->searchUserRange == 1) {
                $whereParam[] = 'userId = ?';
                $bindValues[] = $this->searchUserId;
            }

            $sql = 'SELECT ' . implode(',', $selectParam) . ' FROM ItunesPaymentTransaction WHERE ' . implode(' AND ', $whereParam);
            if (count($groupParam) > 0) {
                $sql .= ' GROUP BY ' . implode(',', $groupParam);
            }
            if (count($orderParam) > 0) {
                $sql .= ' ORDER BY ' . implode(',', $orderParam);
            }
            $command = Yii::app()->db->createCommand($sql);
            if (count($bindValues) > 0) {
                $command->bindValues($bindValues);
            }
            return $command->queryAll();
        }
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

    public static function parseDayStr($dayStr) {
        if (preg_match("/^(\d*)\/(\d*)\/(\d*)$/", $dayStr, $matchs)) {
            return mktime(0, 0, 0, intval($matchs[2]), intval($matchs[3]), intval($matchs[1]));
        }
    }

}