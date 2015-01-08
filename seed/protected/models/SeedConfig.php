<?php
class SeedConfig {
    public static function isDebug() {
        return !empty(Yii::app()->params['testMode']) ;
    }

    public static function isUserGuide() {
        return true ;
    }

    public static function isShowMenu() {
        
    }

    public static function getGameVersion() {
        return self::isDebug()?time():SeedVersion::getVersion();
    }
    
    public static function getSize() {
    	return Yii::app()->params['size'];
    }
    
    public static function setSize($isipad) {
    	switch ($isipad) {
    		case 1:
    			return 1;
    			break;
    		default:
    			return 0.5;
    			break;
    	}
    }

}
