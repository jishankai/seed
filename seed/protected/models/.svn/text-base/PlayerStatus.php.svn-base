<?php

class PlayerStatus extends CBehavior {

    private $statusArray;
    private $statusKeys = array(
        'mapId', 'guideLevel', 'guideCompleted', 'visualFoster', 'breedCDTime', 'chargeCDTime', 'noticeStartTime', 'newMailNum', 'snsContent','globalMessageTime',
    );
    private $playerId;

    public function __construct($playerId) {
        $this->playerId = $playerId;
    }

    public function init() {
        if (!empty($this->statusKeys) && !empty($this->statusArray)) {
            return true;
        }

        $command = Yii::app()->db->createCommand();
        $result = $command->select('*')->from('playerStatus')->where('playerId=' . intval($this->playerId))->queryRow();

        if (!$result) {
            $dataArray = array(
                'playerId' => $this->playerId,
                'createTime' => time(),
            );

            DbUtil::insert(Yii::app()->db, 'playerStatus', $dataArray);
            $result = $dataArray;
        }
        $this->statusArray = $result;
    }

    public function setStatus($key, $value = null) {
        $this->init();

        if (!is_array($key)) {
            $key = array($key => $value);
        }
        $dataArray = array();
        foreach ($key as $k => $v) {
            if (!in_array($k, $this->statusKeys)) {
                throw new CException("Status key {$k} not exists.");
            }

            if (!isset($this->statusArray[$k]) || $this->statusArray[$k] != $v) {
                $this->statusArray[$k] = $v;
            }
            $dataArray[$k] = $v;
        }
        if (!empty($dataArray)) {
            DbUtil::update(Yii::app()->db, 'playerStatus', $dataArray, array('playerId' => intval($this->playerId)));
        }
    }

    public function getStatus($key) {
        $this->init();

        if (isset($this->statusArray[$key])) {
            return $this->statusArray[$key];
        } elseif (!in_array($key, $this->statusKeys)) {
            throw new CException("Status key {$key} not exists.");
        } else {
            return false;
        }
    }

}

