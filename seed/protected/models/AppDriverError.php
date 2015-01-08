<?php
/**
 * AppDriverError
 **/
class AppDriverError extends RecordModel
{
    public $id;
    
    function __construct($id=null)
    {
        $this->id = $id;
    }

    public static function attributeColumns()
    {
        return array(
            'ip', 'url', 'count', 'createTime', 'updateTime',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM appDriverError WHERE id = :id");
        $rowData = $command->bindParam(':id', $this->id)->queryRow();
        
        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'appDriverError', $attributes, array('id'=>$this->id));
    } 

    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "SELECT * FROM appDriverError";
		$conditions = array();
		$bindValues = array() ;
		if( isset($params['id']) ) {
			$conditions[] = 'id=:id';
			$bindValues[':id'] = $params['id'] ;
		}
		
		if( !empty($conditions) ){
		    $sql .= ' WHERE '.implode(' AND ',$conditions);
		}
		
        return self::multiLoadBySql($sql, 'id', array(), $isSimple);
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

        return DbUtil::insert(Yii::app()->db, 'appDriverError', $insertArr, true);
    }

    public static function findIdByIP($ip)
    {
        $command = Yii::app()->db->createCommand("SELECT id FROM appDriverError WHERE ip = :ip");
        $command->bindValue(':ip', $ip);
        $id = $command->queryScalar();

        return $id;   
    }
}
?>
