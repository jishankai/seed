<?php
/**
 * FriendInfo
 **/
class FriendInfo extends RecordModel
{
    public $playerId;

    public function __construct($playerId)
    {
        $this->playerId = $playerId;
    }

    public static function attributeColumns()
    {
        return array(
            'playerId', 'randomList', 'randomTime', 'isAddFriend',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM friendInfo WHERE playerId = :playerId");
        $rowData = $command->bindParam(':playerId', $this->playerId)->queryRow(); 

        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'friendInfo', $attributes, array('playerId'=>$this->playerId));
    }

    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "select * from friendInfo";
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
        DbUtil::insert(Yii::app()->db, 'friendInfo', $insertArr, false);
    }
	
	/*���κ����������*/
	public static function addFriendON($playerId) {
		$sql = 'UPDATE friendInfo SET isAddFriend = 1 WHERE playerId = :playerId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
		$command->execute();
	}
	
	public static function addFriendOFF($playerId) {
		$sql = 'UPDATE friendInfo SET isAddFriend = 0 WHERE playerId = :playerId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
		$command->execute();
	}
	
	public static function addFriendFlag($playerId) {
		$sql = 'SELECT isAddFriend FROM friendInfo WHERE playerId = :playerId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
		return $command->queryScalar();
	}
	
	/*��ȡ��������ѵ�CD*/
	
	public static function getCDTime($playerId) {
		$sql = 'SELECT randomTime FROM friendInfo WHERE playerId = :playerId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
		$time1 = $command->queryScalar();
		$time2 = time();
		$time1-$time2<0?$time = 0:$time = ($time1 - $time2);
		return $time;
	}
	
	public static function setCDTime($playerId) {
		$time1 = time();
		$time2 = $time1+RANDOM_CDTIME;
		$sql = 'UPDATE friendInfo SET randomTime = :cdtime WHERE playerId = :playerId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
		$command->bindParam(':cdtime', $time2);
		$command->execute();
	}
	
	public static function saveRandomList($playerId, $idList) {
		$sql = 'UPDATE friendInfo SET randomList = :randomList WHERE playerId = :playerId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
		$command->bindParam(':randomList', $idList);
		$command->execute();
	}
	
	public static function getRandomList($playerId) {
		$sql = 'SELECT randomList FROM friendInfo WHERE playerId = :playerId';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':playerId', $playerId);
		return $command->queryScalar();
	}
}
?>