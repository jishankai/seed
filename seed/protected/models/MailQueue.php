<?php

class MailQueue extends RecordModel {

    public $queueId;
    public $goodsId;
    public $goodsNum;
    public $seedId;
    public $seedNum;
    public $sentGold;
    public $sentGoldNum;
    public $sentMoney;
    public $sentMoneyNum;

    public function __construct($queueId) {
        $this->queueId = $queueId;
    }

    public function checkOwner($playerId) {
        if (!$this->isOwner($playerId)) {
            throw new CException(Yii::t('Mail', 'the mail queue not belong you'));
        }
    }

    public function isOwner($playerId) {
        return ($this->playerId == $playerId) ? true : false;
    }

    public static function attributeColumns() {
        return array(
            'queueId', 'playerId', 'getDays', 'keepDays', 'informType', 'fromId', 'mailTitle', 'content', 'sentThings', 'isMoveOut'
        );
    }

    protected function loadData() {
        $command = Yii::app()->db->createCommand('SELECT * FROM mailQueue WHERE queueId=:queueId');
        $rowData = $command->bindParam(':queueId', $this->queueId)->queryRow();
        return $rowData;
    }

    protected function saveData($attributes = array()) {
        return DbUtil::update(Yii::app()->db, 'mailQueue', $attributes, array('queueId' => $this->queueId));
    }

    public static function multiLoad($params = array(), $isSimple = true) {
        $sql = "SELECT * FROM mailQueue";
        if (!empty($params)) {
            $sql .= ' WHERE ' . implode(' AND ', $params);
        }
        return self::multiLoadBySql($sql, 'queueId', array(), $isSimple);
    }

    public static function create($createInfo) {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();
        return DbUtil::insert(Yii::app()->db, 'mailQueue', $insertArr, true);
    }

    public static function queueCount($playerId) {
        $command = Yii::app()->db->createCommand("SELECT COUNT(*) FROM mailQueue WHERE playerId = :playerId AND isMoveOut = 0");
        $command->bindParam(':playerId', $playerId);
        return $command->queryColumn();
    }

    public static function getMaxQueueByPlayer($playerId) {
        $command = Yii::app()->db->createCommand("SELECT queueId, Min(createTime) AS minData FROM mailQueue WHERE playerId = :playerId AND isMoveOut = 0");
        $command->bindParam(':playerId', $playerId);
        return $command->queryRow();
    }

}