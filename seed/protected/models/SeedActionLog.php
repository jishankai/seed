<?php
class SeedActionLog {
    public static $actionTypes = array(
        'equip','sell','feed','get',
    ); 

    public static function save( $seedId,$actionType,$actionDesc,$playerId=0 ) {
        if( !in_array($actionType,self::$actionTypes) ) {
            return ;
            //throw new CException('Action type not defined.');
        }
        $array = array(
            'seedId'    => $seedId ,
            'actionType'=> $actionType ,
            'actionDesc'=> $actionDesc ,
            'playerId'  => $playerId ,
            'createTime'=> time() ,
        );
        return DbUtil::insert( Yii::app()->db,'seedActionLog',$array );
    }
}
