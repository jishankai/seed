<?php

class SimpleObjectLoader extends CApplicationComponent{
    private $_attribute;
    
    public function __construct(){
        $this->_attribute = array();
    }

    public function init(){
        parent::init();
    }
    
    public function load($objectName, $uniqueId = 0){
        if( !($object = $this->get($objectName, $uniqueId)) ){
            if( empty($uniqueId) ) {
                $object = new $objectName ;
            }
            else {
                $object = new $objectName($uniqueId);
            }
            $this->set($objectName, $uniqueId, $object);
        }
        return $object;
    }
    
    public function get($objectName, $uniqueId){
        if(isset($this->_attribute[$objectName][$uniqueId])){
            return $this->_attribute[$objectName][$uniqueId];
        }
        return null;
    }

    public function set($objectName, $uniqueId, $object){
        return $this->_attribute[$objectName][$uniqueId] = $object;
    }
}