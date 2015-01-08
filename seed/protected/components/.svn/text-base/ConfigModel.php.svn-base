<?php
abstract class ConfigModel extends CModel {
    private $_attributes = array();
    private static $_configData = array();
    
    public static function getConfigName(){
        return get_called_class();
    }
    
    public function __construct($pk) {
        $configKey = static::getConfigName() ;
        self::loadConfigData($configKey);
        if (isset(self::$_configData[$configKey][$pk])) {
            $this->_attributes = self::$_configData[$configKey][$pk];
        }
        else {
            throw new CException('Config data not exists.');
        }
    }
    
    public function attributeNames() {
        return array_keys($this->_attributes);
    }
    
    public function __get($name) {
        if (in_array($name, $this->attributeNames())) {
            return $this->_attributes[$name];
        }
        else {
            return parent::__get($name);
        }
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
        return isset($this->_attributes[$name]) ;
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
    
    protected static function getMultiData( $params=array(),$configKey='' ){
        if( empty($configKey) ) {
            $configKey = static::getConfigName() ;
        }
        self::loadConfigData($configKey);
        $dataArray = array();
        foreach( self::$_configData[$configKey] as $key=>$rowData ) {
            $isContinue = false ;
            foreach ($params as $k=>$v) {
                if( $rowData[$k]!=$v ) {
                    $isContinue = true ;
                    break ; 
                }
            }
            if( $isContinue ) {
                continue ;
            }
            $dataArray[$key] = Yii::app()->objectLoader->load(get_called_class(),$key);
        }
        return $dataArray ;
    }
    
    private static function loadConfigData($configKey){
        if( !isset(self::$_configData[$configKey]) ) {
            self::$_configData[$configKey] = Util::loadconfig( $configKey );
        }
    }
    
}

