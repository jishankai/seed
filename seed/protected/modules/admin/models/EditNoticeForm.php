<?php

class EditNoticeForm extends CFormModel {

    public $title;
    public $notice;
    public $startTime;
    public $endTime;
    public $deleteFlag;

    public function rules() {
        return array(
            array('title, notice, startTime', 'required'),
            array('startTime, endTime', 'checkTime'),
            array('endTime', 'comparisonTime'),
            array('startTime', 'checkStartTime'),
        );
    }

    public function fillData($notice) {
        $this->title = $notice['title'];
        $this->notice = $notice['notice'];
        $this->startTime = self::makeTimeStr($notice['startTime']);
        $this->endTime = self::makeTimeStr($notice['endTime']);
        //$this->deleteFlag = $notice['deleteFlag'];
    }

    public function checkTime($attribute, $params) {
        if (($this->$attribute != '') and (!preg_match("/^\d{4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/", $this->$attribute))) {
            $this->addError($attribute, $attribute . ':time format error');
        }
    }

    public function comparisonTime($attribute, $params) {
        if (strtotime($this->startTime) > strtotime($this->endTime))
            $this->addError($attribute, $attribute . ':endTime less than the startTime');
    }

    public function checkStartTime($attribute, $params) {
        if (($this->startTime != '') and (strtotime($this->startTime) < time())) {
            $this->addError($attribute, $attribute . ':startTime less than the current time');
        }
    }

    public function checkDeleteFlag($attribute, $params) {
        if (!preg_match("/^(0|1)$/", $this->$attribute)) {
            $this->addError($attribute, $attribute . 'should be 0 or 1');
        }
    }

    public function updateData($noticeId) {
        if (!$this->hasErrors()) {
            $startTime = self::parseTimeStr($this->startTime);
            $endTime = self::parseTimeStr($this->endTime);
            $command = Yii::app()->db->createCommand("UPDATE mailNotice SET title = :title, notice = :notice, startTime = :startTime, endTime = :endTime WHERE noticeId = :noticeId");
            $command->execute(array(':title' => $this->title, ':notice' => $this->notice, ':startTime' => $startTime, ':endTime' => $endTime, ':noticeId' => $noticeId));
        }
    }

    public function addData() {
        if (!$this->hasErrors()) {
            $startTime = self::parseTimeStr($this->startTime);
            $endTime = self::parseTimeStr($this->endTime);
            $createTime = time();
            $command = Yii::app()->db->createCommand("INSERT INTO mailNotice (title, notice, createTime, startTime, endTime) VALUES (:title, :notice, :createTime, :startTime, :endTime)");
            $command->execute(array(':title' => $this->title, ':notice' => $this->notice, ':createTime' => $createTime, ':startTime' => $startTime, ':endTime' => $endTime));
            return Yii::app()->db->getLastInsertId();
        }
    }

    public static function makeTimeStr($timestamp) {
        if (!isset($timestamp)) {
            return null;
        }
        return date('Y-m-d H:i:s', $timestamp);
    }

    public static function parseTimeStr($timeStr) {
        if (preg_match("/^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/", $timeStr, $matchs)) {
            return mktime(intval($matchs[4]), intval($matchs[5]), intval($matchs[6]), intval($matchs[2]), intval($matchs[3]), intval($matchs[1]));
        }
        return null;
    }

}