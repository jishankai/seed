<?php
class SException extends CException{
    private static $params ;

    public function __construct( $message, $code='-1', $params=array() ) {
        self::$params[$code] = $params ;
        parent::__construct( $message, $code );
    }

    public static function getParams( $code ) {
        return isset(self::$params[$code])?self::$params[$code]:false ;
    }
}
?>
