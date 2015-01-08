<?php
/**
 * 图鉴定义信息
 * 
 * 
 */
class CatalogData extends ConfigModel {
    public $dataId ;
    
    /**
     * 构造函数
     * 
     * @param $dataId 数据ID
     */
    public function __construct($dataId){
        $this->dataId = $dataId ;
        parent::__construct($this->dataId) ;
    }


    public function getName(){
        return Yii::t('Catalog',$this->name);
    }

    public function getDesc(){
        return Yii::t('Catalog',$this->desc);
    }

    public static function getDisplayData(){
        //
    }

    public static function getKey( $type,$bodyId ) {
        return $type*100000+$bodyId ;
    }

    public static function getAll() {
        return self::getMultiData();
    }

    public static function getAllCount() {
        return count( self::getMultiData() );
    }

    public static function getBodyAll( $bodyId ) {
        return self::getMultiData( array('bodyId'=>$bodyId) );
    }

}