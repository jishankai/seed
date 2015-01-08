<?php
/**
 * PlayerPoint
 *
 * @packaged default
 * @author Ji.Shankai
 **/
class PlayerPoint extends RecordModel
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public static function attributeColumns()
    {
        return array(
            'playerId', 'type', 'max', 'value', 'refreshTime', 'changeValue', 'changeInterval', 'createTime',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM playerPoint WHERE id = :id");
        $rowData = $command->bindParam(':id', $this->id)->queryRow();
        
        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'playerPoint', $attributes, array('id'=>$this->id));
    }

    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "select * from playerPoint";
        if (!empty($params)) {
            $sql .= 'where '.implode('and', $params);
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

        return DbUtil::insert(Yii::app()->db, 'playerPoint', $insertArr, true);
    }

    private static function findId($playerId, $type)
    {
        $command = Yii::app()->db->createCommand("SELECT id FROM playerPoint WHERE playerId = :playerId AND type = :type");
        $values = array(
            ':playerId' => $playerId,
            ':type' => $type,
        );
        return $command->bindValues($values)->queryScalar();
    }

    public static function loadDataByModel($playerId, $type) {
        $id = self::findId($playerId, $type);

        if ($id !== false) {
            return Yii::app()->objectLoader->load('PlayerPoint', $id);
        }else {
            return null;
        }
    }
} // END class PlayerPoint
?>
