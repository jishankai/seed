<?php
/**
 * User
 **/
class User extends RecordModel
{
    public $userId;
    
    function __construct($userId=null)
    {
        $this->userId = $userId;
    }

    public static function attributeColumns()
    {
        return array(
            'deviceId', 'token', 'createTime', 'updateTime',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM user WHERE userId = :userId");
        $rowData = $command->bindParam(':userId', $this->userId)->queryRow();
        
        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'user', $attributes, array('userId'=>$this->userId));
    } 

    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "SELECT * FROM user";
		$conditions = array();
		$bindValues = array() ;
		if( isset($params['userId']) ) {
			$conditions[] = 'userId=:userId';
			$bindValues[':userId'] = $params['userId'] ;
		}
		
		if( !empty($conditions) ){
		    $sql .= ' WHERE '.implode(' AND ',$conditions);
		}
		
        return self::multiLoadBySql($sql, 'userId', array(), $isSimple);
    } 

    public static function create($createInfo)
    {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();

        return DbUtil::insert(Yii::app()->db, 'user', $insertArr, true);
    }

    public static function findIdByDevice($deviceId)
    {
        $command = Yii::app()->db->createCommand("SELECT userId FROM user WHERE deviceId = :deviceId");
        $command->bindValue(':deviceId', $deviceId);
        $userId = $command->queryScalar();

        return $userId;   
    }

    public static function findIdByToken($token)
    {
        $command = Yii::app()->db->createCommand("SELECT userId FROM user WHERE token = :token");
        $command->bindValue(':token', $token);
        $userId = $command->queryScalar();

        return $userId;   
    }
}
?>
