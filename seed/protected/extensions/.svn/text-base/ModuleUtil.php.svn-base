<?php

class ModuleUtil {
    public static function loadconfig($ModuleName,$k,$cache=true,$params = null){
        static $cfg;
        if(!$cfg){
            $cfg = array();
        }
        $key = $ModuleName.'_'.$k;
        if(!isset($cfg[$key]) || !$cache){
            if($params){
                foreach($params as $name => $value){
                    $$name = $value;
                }
            }
            if(file_exists(dirname(__FILE__).'/../modules/'.$ModuleName.'/config/'.$k.'.cfg.php')){
                $cfg[$key] = require(dirname(__FILE__).'/../modules/'.$ModuleName.'/config/'.$k.'.cfg.php');
            }
        }
        if(isset($cfg[$key])){
            return $cfg[$key];
        }else{
            return null;
        }
    }
}
?>