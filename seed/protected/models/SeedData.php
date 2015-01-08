<?php
/**
 * 种子基础结构
 * 种子各个部分为Id
 * 
 */
class SeedData extends ConfigModel {
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
      
    /**
     * 获得名字
     * 
     */
    public function getName(){
        return Yii::t('SeedData',$this->dataName);
    }
    
    public function checkLevel( $level ) {
        if( $this->unlockLevel>$level ) {
            throw new SException( Yii::t('Seed','your level is not up to unlock level.') );
        }
    }

    
    public static function getBodyData(){
        $params = array('dataType'=>1);
        return static::getMultiData($params);
    }
    public static function getFaceData(){
        $params = array('dataType'=>2);
        return static::getMultiData($params);
    }
    public static function getBudData(){
        $params = array('dataType'=>3);
        return static::getMultiData($params);
    }
    public static function getDressData(){
        $params = array('dataType'=>4);
        return static::getMultiData($params);
    }

    public static function getPlayerUnlockData($playerId,$dataLevels=array()) {
        $player = Yii::app()->objectLoader->load('Player',$playerId);
        $data = array();
        foreach( array('body','face','bud','dress') as $key ) {
            $method = "get{$key}Data";
            $data[$key] = array(); 
            foreach( self::$method() as $d ) {
                if( $d->unlockLevel<=$player->level&&( empty($dataLevels[$key])||$d->level<=$dataLevels[$key] ) )
                $data[$key][$d->dataId] = $d ;
            }
        }
        return $data ;
    }
    
    public static function getRandomSeedData( $playerId, $seedMinLevel, $seedMaxLevel ) {
    	$player = Yii::app()->objectLoader->load('Player',$playerId);
    	$data = array();
    	foreach( array('body','face','bud') as $key ) {
    		$method = "get{$key}Data";
            $data[$key] = array();
    		foreach( self::$method() as $d ) {
                if ( ($d->unlockLevel<=$player->level)&&( $d->level>=$seedMinLevel ) && ($d->level<=$seedMaxLevel) ) {
                	$data[$key][$d->dataId] = $d ;
                }
            }
    	}
        return $data;
    }

    public static function getAllName( $bodyId,$faceId=0,$budId=0,$dressId=0 ) {
        $budName = $faceName = $bodyName = $dressName = '';
        if( !empty($dressId) ) {
            $dressData = Yii::app()->objectLoader->load('SeedData',$dressId);
            $dressName = $dressData->getName();
        }
        if( !empty($budId) )
            $budName = Yii::app()->objectLoader->load('SeedData',$budId)->getName();
        if( !empty($faceId) )
            $faceName = Yii::app()->objectLoader->load('SeedData',$faceId)->getName() ;
        if( !empty($bodyId) )
            $bodyName = Yii::app()->objectLoader->load('SeedData',$bodyId)->getName() ;
        
        return $budName.$faceName.$bodyName.$dressName ;
    }

    public static function getAllDisplayData($bodyId,$faceId=0,$budId=0,$dressId=0) {
        $result = array();
        if( !empty($dressId) )
            $result[] = Yii::app()->objectLoader->load('SeedData',$dressId)->getDisplayData();
        if( !empty($budId) )
            $result[] = Yii::app()->objectLoader->load('SeedData',$budId)->getDisplayData();
        if( !empty($faceId) )
            $result[] = Yii::app()->objectLoader->load('SeedData',$faceId)->getDisplayData();
        if( !empty($bodyId) )
            $result[] = Yii::app()->objectLoader->load('SeedData',$bodyId)->getDisplayData();

        return json_encode( $result );
    }

    public function getXmlFile() {
        return dirname(__FILE__).'/../../images/seed/'.$this->dataName.'.xml';
    }

    public function getImageUrl() {
        return 'images/seed/'.$this->dataName.'.png?v='.SeedVersion::getVersion() ;
    }

    public function getDisplayData( $index=0 ) {
        $xml = simplexml_load_file( $this->getXmlFile() ); 

        $data = $xml->dict->dict->dict[$index] ;
        $result = array();
        
        $result['frame']        = self::getFormatedData( $data->string[0] );
        $result['offset']       = self::getFormatedData( $data->string[1] );
        $result['rotated']      = isset( $data->true );
        $result['sourceColorRect']   = self::getFormatedData( $data->string[2] );
        $result['sourceSize']   = self::getFormatedData( $data->string[3] ) ;
        $result['source']       = $this->getImageUrl() ;
        $result['index']        = $this->getDisplayIndex() ;
        return $result ;
    }
    
    public static function getFormatedData($string) {
        $string = str_replace( array('{','}'),array('[',']'),$string );
        return json_decode( $string );
    }

    public function getDisplayIndex() {
        $indexs = array(
            1 => 2 ,
            2 => 3 ,
            3 => 5 ,
            //4 => 1 ,
        );
        $i = floor($this->dataId/1000) ;
        if( $i==4 ) {
            $configData = Util::loadConfig('SeedDressData');
            $indexs[4] = !empty($configData[$this->dataId]['position'])?4:1 ;
        }
        return isset($indexs[$i])?$indexs[$i]:1;
    }

}