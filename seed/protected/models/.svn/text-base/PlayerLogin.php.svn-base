<?php
/**
 * PlayerLogin
 **/
class PlayerLogin extends RecordModel
{
    public $playerId;

    public function __construct($playerId)
    {
        $this->playerId = $playerId;
    }

    public static function attributeColumns()
    {
        return array(
            'playerId', 'lastLoginTime', 'loginDays', 'rewardInfo',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM playerLogin WHERE playerId = :playerId");
        $rowData = $command->bindParam(':playerId', $this->playerId)->queryRow(); 
        if (empty($rowData)) {
            self::create(array('playerId'=>$this->playerId));
        }
        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'playerLogin', $attributes, array('playerId'=>$this->playerId));
    }

    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "select * from playerLogin";
        if (!empty($params)) {
            $sql .= 'where '.implode('and', $params);
        }

        return self::multiLoadBySql($sql, 'playerId', array(), $isSimple);
    } 

    public static function create($createInfo) {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if(isset($createInfo[$key])){
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();
        DbUtil::insert(Yii::app()->db, 'playerLogin', $insertArr, false);
    }
}
?>
