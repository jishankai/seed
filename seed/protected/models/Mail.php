<?php 

class Mail extends RecordModel {

    public $mailId;
    public $goodsId;
    public $goodsNum;
    public $seedId;
    public $seedNum;
    public $sentGold;
    public $sentGoldNum;
    public $sentMoney;
    public $sentMoneyNum;

    public function __construct($mailId) {
        $this->mailId = $mailId;
    }

    public function checkOwner($playerId) {
        if (!$this->isOwner($playerId)) {
            throw new CException(Yii::t('Mail', 'the mail not belong you'));
        }
    }

    public function checkIsGet() {
        if ($this->isGet == 1) {
            throw new CException(Yii::t('Mail', 'the mail is not in db'));
        }
    }

    public function isOwner($playerId) {
        return ($this->playerId == $playerId) ? true : false;
    }

    public static function attributeColumns() {
        return array(
            'mailId', 'playerId', 'isGet', 'getDays', 'keepDays', 'informType', 'fromId', 'mailTitle', 'content', 'sentThings', 'isRead', 'action', 'createTime'
        );
    }

    protected function loadData() {
        $command = Yii::app()->db->createCommand('SELECT * FROM mail WHERE mailId=:mailId AND action = 0');
        $rowData = $command->bindParam(':mailId', $this->mailId)->queryRow();
        return $rowData;
    }

    protected function saveData($attributes = array()) {
        return DbUtil::update(Yii::app()->db, 'mail', $attributes, array('mailId' => $this->mailId));
    }

    public static function multiLoad($params = array(), $isSimple = true) {
        $sql = "SELECT * FROM mail";
        if (!empty($params)) {
            $sql .= ' WHERE 1=1 ' . implode(' ', $params);
        }
        return self::multiLoadBySql($sql, 'mailId', array(), $isSimple);
    }

    public static function create($createInfo) {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();
        $mailId = DbUtil::insert(Yii::app()->db, 'mail', $insertArr, true);
        Yii::app()->objectLoader->load('GlobalMessage', $insertArr['playerId'])->addMessage($mailId, MESSAGE_TYPE_NEW_MAIL);
        return $mailId;
    }

    public static function delMail($mailId) {
        $command = Yii::app()->db->createCommand("UPDATE mail SET isRead = 1,isGet = 1,action = 1 WHERE action = 0 AND mailId = :mailId");
        $command->execute(array(':mailId' => $mailId));
    }

    public static function systemMailCount($playerId) {
        $command = Yii::app()->db->createCommand("SELECT COUNT(*) FROM mail WHERE action = 0 AND informType = 1 AND playerId = :playerId");
        $command->bindParam(':playerId', $playerId);
        return $command->queryColumn();
    }

    public static function mailCount($playerId) {
        $command = Yii::app()->db->createCommand("SELECT COUNT(*) FROM mail WHERE action = 0 AND informType = 2 AND playerId = :playerId");
        $command->bindParam(':playerId', $playerId);
        return $command->queryColumn();
    }

    public static function unReadMailCount($playerId, $informType = -1) {
        if ($informType == -1) {
            $command = Yii::app()->db->createCommand("SELECT COUNT(*) FROM mail WHERE action = 0 AND isRead = 0 AND playerId = :playerId");
        } else {
            $command = Yii::app()->db->createCommand("SELECT COUNT(*) FROM mail WHERE action = 0 AND isRead = 0 AND playerId = :playerId AND informType = :informType");
            $command->bindParam(':informType', $informType);
        }
        $command->bindParam(':playerId', $playerId);
        return $command->queryColumn();
    }

    public static function updateReadMailCount($playerId, $informType) {
        $command = Yii::app()->db->createCommand("UPDATE mail SET isRead = 1 WHERE action = 0 AND playerId = :playerId AND informType = :informType");
        $command->execute(array(':playerId' => $playerId, ':informType' => $informType));
    }

    public static function mailToHistory() {
        $command = Yii::app()->db->createCommand("INSERT INTO mailHistory (mailId,playerId,isGet,getDays,keepDays,informType,fromId,content,sentThings,isRead,action,createTime,updateTime)
                                                  SELECT mailId,playerId,isGet,getDays,keepDays,informType,fromId,content,sentThings,isRead,action,createTime,updateTime FROM mail WHERE action = 1");
        return $command->execute();
    }

    public static function delOldMail() {
        $command = Yii::app()->db->createCommand("DELETE FROM mail WHERE action = 1");
        return $command->execute();
    }

}