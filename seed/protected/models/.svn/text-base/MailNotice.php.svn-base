<?php

class MailNotice extends RecordModel {

    public $noticeId;

    public function __construct($noticeId) {
        $this->noticeId = $noticeId;
    }

    public function checkOwner($playerId) {
        if (!$this->isOwner($playerId)) {
            throw new SException(Yii::t('Mail', 'the mail notice not belong you'));
        }
    }

    public function isOwner($playerId) {
        return ($this->playerId == $playerId) ? true : false;
    }

    public static function attributeColumns() {
        return array(
            'noticeId', 'title', 'notice', 'createTime', 'startTime',
        );
    }

    protected function loadData() {
        $command = Yii::app()->db->createCommand('SELECT * FROM mailNotice WHERE noticeId=:noticeId');
        $rowData = $command->bindParam(':noticeId', $this->noticeId)->queryRow();
        return $rowData;
    }

    protected function saveData($attributes = array()) {
        return DbUtil::update(Yii::app()->db, 'notice', $attributes, array('noticeId' => $this->noticeId));
    }

    public static function multiLoad($params = array(), $isSimple = true) {
        $sql = "SELECT * FROM mailNotice";
        if (!empty($params)) {
            $sql.= ' WHERE startTime < ' . time() . ' and endTime > ' . time() . ' ';
            $sql .= implode(' ', $params);
        }
        return self::multiLoadBySql($sql, 'noticeId', array(), $isSimple);
    }

    public static function create($createInfo) {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();
        return DbUtil::insert(Yii::app()->db, 'mailNotice', $insertArr, true);
    }

    public static function saveNotice($title, $notice) {
        $array = array(
            'title' => $title,
            'notice' => $notice,
            'createTime' => time(),
        );
        return DbUtil::insert(Yii::app()->db, 'mailNotice', $array);
    }

    public static function mailNoticeCount() {
        $command = Yii::app()->db->createCommand("SELECT COUNT(*) FROM mailNotice");
        return $command->queryColumn();
    }

    public static function getNoticeStartTime() {
        //查询满足条件下的startTime
        $command = Yii::app()->db->createCommand('SELECT noticeId,startTime FROM mailNotice WHERE startTime < :nowTime and endTime > :nowTime order by startTime desc limit 0,1');
        $nowTime = time();
        return $command->bindParam(':nowTime', $nowTime)->queryRow();
    }

}
