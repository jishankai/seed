<?php
/**
 * AppDriverPoint
 **/
class AppDriverPoint extends RecordModel
{
    public $id;
    
    function __construct($id=null)
    {
        $this->id = $id;
    }

    public static function attributeColumns()
    {
        return array(
            'achieve_id', 'identifier', 'point', 'payment', 'campaign_id', 'campaign_name', 'advertisement_id', 'advertisement_name', 'accepted_time', 'url', 'status', 'createTime', 'updateTime',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM appDriverPoint WHERE id = :id");
        $rowData = $command->bindParam(':id', $this->id)->queryRow();
        
        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'appDriverPoint', $attributes, array('id'=>$this->id));
    } 

    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "SELECT * FROM appDriverPoint";
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

        return DbUtil::insert(Yii::app()->db, 'appDriverPoint', $insertArr, true);
    }
    
    public static function findIdByAchieveId($achieveId)
    {
        $command = Yii::app()->db->createCommand("SELECT id FROM appDriverPoint WHERE achieve_id = :achieve_id");
        $command->bindValue(':achieve_id', $achieveId);
        $id = $command->queryScalar();

        return $id;   
    }
}
?>
