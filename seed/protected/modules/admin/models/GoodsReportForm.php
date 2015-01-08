<?php

class GoodsReportForm extends CFormModel {

    public $searchUserRange = 0;
    public $searchUserId;
    public $searchGoodsRange = 0;
    public $searchGoodsId;
    public $resultTimeType = 0;
    public $startDate;
    public $endDate;

    public function rules() {
        return array(
            array('searchUserRange, searchGoodsRange, resultTimeType, startDate, endDate', 'required'),
            array('searchUserRange', 'checkUserRange'),
            array('searchGoodsRange', 'checkGoodsRange'),
            array('searchUserId', 'checkUserId'),
            array('searchGoodsId', 'checkGoodsId'),
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

    public function checkGoodsRange() {
        if (!$this->hasErrors()) {
            if ($this->searchGoodsRange != 0 && $this->searchGoodsRange != 1) {
                $this->addError('searchGoodsrRange', 'Not in GoodsRange');
            }
        }
    }

    public function checkUserId($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->searchUserRange == 1) {
                if (isset($this->searchUserId) and preg_match("/^\d+$/", $this->searchUserId)) {
                    /*
                	$user = new User();
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

    public function checkGoodsId($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->searchGoodsRange == 1) {
                if (isset($this->searchGoodsId) and preg_match("/^\d+(,\d+)*$/", $this->searchGoodsId)) {
                    //do nothing
                } else {
                    $this->addError('searchGoodsId', 'Incorrect searchGoodsId');
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
        $selectParam = array('count(*) AS num', 'sum(gold) AS gold', 'linkId');
        $whereParam = array('createTime >= ?', 'createTime < ?');
        $bindValues = array(1 => $startTime, 2 => $endTime);
        $groupParam = array();
        $orderParam = array();
        //$goodsId = array();

        if ($this->searchUserRange == 1) {
            $whereParam[] = 'userId = ?';
            $bindValues[] = $this->searchUserId;
        }
        if ($this->resultTimeType == 1) {//日别
            $selectParam = array('count(*) AS num', 'sum(gold) AS gold', 'FROM_UNIXTIME(createTime, "%Y/%m/%d") AS timeStr');
            //$selectParam[] = 'FROM_UNIXTIME(logTime, "%Y/%m/%d") AS timeStr';
            $groupParam[] = 'timeStr';
            $orderParam[] = 'timeStr ASC';
        } else if ($this->resultTimeType == 2) {//月别
            $selectParam = array('count(*) AS num', 'sum(gold) AS gold', 'FROM_UNIXTIME(createTime, "%Y/%m") AS timeStr');
            //$selectParam[] = 'FROM_UNIXTIME(logTime, "%Y/%m") AS timeStr';
            $groupParam[] = 'timeStr';
            $orderParam[] = 'timeStr ASC';
        }

        if ($this->searchGoodsRange == 1) {
            $linkId = explode(',', $this->searchGoodsId);
            foreach ($linkId as &$value) {
                $value = 'linkId = ' . $value;
            }
            if ($this->resultTimeType == 0) {
                $groupParam[] = 'linkId';
                $orderParam[] = 'linkId ASC';
            }
            //$whereParam[] = 'goodsId = ?';
            //$bindValues[] = $this->searchGoodsId;
        } else {
            $whereParam[] = 'linkId > 0';
            if ($this->resultTimeType == 0) {
                $groupParam[] = 'linkId';
                $orderParam[] = 'linkId ASC';
            }
        }

        $sql = 'SELECT ' . implode(',', $selectParam) . ' FROM userMoneyLog WHERE ' . implode(' AND ', $whereParam);
        if ($this->searchGoodsRange == 1) {
            $sql.=' AND (' . implode(' OR ', $linkId) . ')';
        }
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
        //if ($this->resultTimeType != 0) {
        $startTime = self::parseDayStr($this->startDate);
        $endTime = self::parseDayStr($this->endDate) + 86400;
        //$paymentModel = new ItunesPaymentTransaction();
        $selectParam = array('count(*) AS num', 'sum(gold) AS gold');
        $whereParam = array('createTime >= ?', 'createTime < ?');
        $bindValues = array(1 => $startTime, 2 => $endTime);
        $groupParam = array();
        $orderParam = array();

        if ($this->searchUserRange == 1) {
            $whereParam[] = 'userId = ?';
            $bindValues[] = $this->searchUserId;
        }

        if ($this->searchGoodsRange == 1) {
            $linkId = explode(',', $this->searchGoodsId);
            foreach ($linkId as &$value) {
                $value = 'linkId = ' . $value;
            }
            //$whereParam[] = 'goodsId = ?';
            //$bindValues[] = $this->searchGoodsId;
        } else {
            $whereParam[] = 'linkId > 0';
            //$groupParam[] = 'goodsId';
            //$orderParam[] = 'goodsId ASC';
        }

        $sql = 'SELECT ' . implode(',', $selectParam) . ' FROM userMoneyLog WHERE ' . implode(' AND ', $whereParam);
        if ($this->searchGoodsRange == 1) {
            $sql.=' AND (' . implode(' OR ', $linkId) . ')';
        }
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
        //}
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