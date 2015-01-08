<?php
/**
 * 基础数据类 RecordModel
 * 
 * @author user
 *
 */
abstract class RecordModel extends CModel implements RecordInterface {
    private $_attributes = array();
    
    protected abstract function loadData();
    protected abstract function saveData($attributes=array());
    
     
    public function attributeNames(){
        return static::attributeColumns();
    }
    
    public function __get($name) {
        if (in_array($name, $this->attributeNames())) {
            if (array_key_exists($name, $this->_attributes)) {
                return $this->_attributes[$name];
            }
            else {
                $rowData = $this->loadData();
                if ( empty($rowData) ) {
                    //return null;
                    throw new CException('Data record not exists.');
                }
                else {
                    $this->loadRecord($rowData);
                }
                
                if (array_key_exists($name, $this->_attributes)) {
                    return $this->_attributes[$name];
                }
                else {
                    return null;
                }
            }
        }
        else {
            return parent::__get($name);
        }
    }
    
    public function loadRecord($rowData) {
        foreach ($this->attributeNames() as $attributeName) {
            if (!array_key_exists($attributeName, $this->_attributes)) {
                if (isset($rowData[$attributeName])) {
                    $this->_attributes[$attributeName] = $rowData[$attributeName];
                }
                else {
                    $this->_attributes[$attributeName] = null;
                }
            }
        }
    }
    
    /**
     * 根据sql语句批量载入数据对象
     * 只给继承的类内部调用 
     * 
     * @param string $sql sql语句
     * @param string $indexCol 用于数据索引的字段名
     * @param array    $params DBCommand对象绑定的数据类型
     * @param boolean $isSimple 是否是简单数据类型
     */
     protected static function multiLoadBySql( $sql,$indexCol='',$params=array(),$isSimple=true ){
         $command = Yii::app()->db->createCommand( $sql );
         $result = $command->bindValues($params)->queryAll();
         $dataArray = array();
         foreach (  $result as $i=>$row ) {
             $index = isset($row[$indexCol])?$row[$indexCol]:$i ;
             if( $isSimple ) {
                 $dataArray[$index] = new stdClass();
                 foreach( static::attributeColumns() as $key ) {
                     $dataArray[$index]->$key = $row[$key] ;
                 }
             }
             else {
                 $dataArray[$index] = Yii::app()->objectLoader->load( get_called_class(),$row[$indexCol] );
                 $dataArray[$index] -> loadRecord($row);
             }
         }
         return $dataArray ;
     }
    
     public function __set($name, $value) {
        if (in_array($name, $this->attributeNames())) {
            $this->_attributes[$name] = $value;
        }
        else {
            parent::__set($name, $value);
        }
    }
    
    public function __isset($name) {
        $this->__get($name);
        if (isset($this->_attributes[$name])) {
            return true;
        }
        else {
            return parent::__isset($name);
        }
    }
    
    public function __unset($name) {
        unset($this->_attributes[$name]);
    }
    
    public function canGetProperty($name) {
        return method_exists($this, 'get' . $name) || in_array($name, $this->attributeNames());
    }
    
    public function canSetProperty($name) {
        return method_exists($this, 'set' . $name) || in_array($name, $this->attributeNames());
    }
    
    public function saveAttributes($attributeNames=array()) {
        $saveAttributes = array();
        foreach ($attributeNames as $attributeName) {
            if (in_array($attributeName, $this->attributeNames())){
                $saveAttributes[$attributeName] = $this->_attributes[$attributeName];
            }
        }
        return $this->save($saveAttributes);
    }
    
    private function save($attributes = array()) {
        return $this->saveData($attributes);
    }
    
    public function reset() {
        $this->_attributes = array();
    }

    public function isExists() {
        $attributeNames = $this->attributeNames();
        $firstKey = current($attributeNames);
        $endKey = end($attributeNames);
        try{
            return isset( $this->$firstKey )&&isset( $this->$endKey ) ;
        } catch (Exception $e) {
            return false ;
        }
    }
}
