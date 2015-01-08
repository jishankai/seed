<?php

class UserTwitter extends RecordModel {

    public $Id;

    public function __construct($Id) {
        $this->Id = $Id;
    }

    public static function attributeColumns() {
        return array(
            'userId', 'systemId', 'systemName', 'createTime', 'userToken', 'userSecret',
        );
    }

    protected function loadData() {
        $command = Yii::app()->db->createCommand('SELECT * FROM userTwitter WHERE Id=:Id');
        $rowData = $command->bindParam(':Id', $this->Id)->queryRow();
        return $rowData;
    }

    protected function saveData($attributes = array()) {
        return DbUtil::update(Yii::app()->db, 'userTwitter', $attributes, array('Id' => $this->Id));
    }

    public static function multiLoad($params = array(), $isSimple = true) {
        $sql = "SELECT * FROM userTwitter";
        if (!empty($params)) {
            $sql .= ' WHERE ' . implode(' AND ', $params);
        }
        return self::multiLoadBySql($sql, 'Id', array(), $isSimple);
    }

    public static function create($createInfo) {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();
        return DbUtil::insert(Yii::app()->db, 'userTwitter', $insertArr, true);
    }

    public static function findIdBySystemId($systemId) {
        $command = Yii::app()->db->createCommand("SELECT userId FROM userTwitter WHERE systemId = :systemId");
        $command->bindValue(':systemId', $systemId);
        $userId = $command->queryScalar();

        return $userId;
    }

    public static function findIdByUserId($userId) {
        $command = Yii::app()->db->createCommand("SELECT id FROM userTwitter WHERE userId = :userId");
        $command->bindValue(':userId', $userId);
        $userId = $command->queryScalar();

        return $userId;
    }

    public static function findTokenByUserId($userId) {
        $command = Yii::app()->db->createCommand("SELECT userToken,userSecret FROM userTwitter WHERE userId = :userId");
        $command->bindParam(':userId', $userId);
        return $command->queryRow();
    }

    public static function delTwitterToken($userId) {
        $command = Yii::app()->db->createCommand("DELETE FROM userTwitter WHERE userId = :userId");
        $command->bindParam(':userId', $userId);
        return $command->execute();
    }

}

?>
