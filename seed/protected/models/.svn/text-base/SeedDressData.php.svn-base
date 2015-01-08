<?php
/**
 * 装饰物配置信息类
 * 
 */
class SeedDressData extends SeedData {
    private $attributeRates ;
    public $position ;
    
    public function __construct($dataId) {
        $configData = Util::loadConfig('SeedDressData');
        if( !isset($configData[$dataId]) ) {
            throw new CException('Config data not exits.') ; 
        }
        $this->attributeRates = $configData[$dataId]['attributes'];
        $this->position = $configData[$dataId]['position'];
        parent::__construct($dataId);
    }

    public static function getConfigName(){
        return 'SeedData';
    }


    /**
     * 
     * 
     */
    public function getAttributeRates(){
        $rates = array() ;
        $sumMax = self::getMaxValue();
        foreach( $this->attributeRates as $k=>$v ) {
            $rates[$k] = $v/$sumMax ;
        }
        return $rates ;
    }
    
    /**
     * 获得这个装备需要的属性满足的值
     * 
     * @param int $maxValue 装备需要的总值
     */
    public function getAttributeValues($maxValue){
        $values = array();
        foreach( $this->getAttributeRates() as $k=>$v ) {
            $values[$key] = ceil($maxValue*$v);
        }
        return $values ;
    }

    /****/
    public function checkValueValid( $attributeArray,$needValue ) {
        foreach( $this->getAttributeRates() as $k=>$v ) {
            if( $attributeArray[$k]<ceil($needValue*$v) ) {
                return false ;
            }
        }
        return true ;
    }
    
    public static function getAllData(){
        return static::getMultiData( array('dataType'=>4) );
    }
    
    public static function getDressByUnlockLevel($playerLevel) {
        $newList = array();
        foreach( self::getAllData() as $dataId=>$obj ) {
            if( $obj->unlockLevel>$playerLevel ) {
                continue ;
            }
            else {
                $newList[$dataId] = $obj ;
            }
        }
        return $newList ;
    }

    public static function getMaxValue() {
        return 5 ;
    }
    
    public function getAttributeSize() {
        return $this->attributeRates ;
    }

    public function getImage() {
        return 'images/dress/'.$this->dataName.'_idle.png';
    }
    
}