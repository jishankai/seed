<?php
/**
 * action判断
 *
 */
class ActionRules extends Model {
    private $rules ;

    public function __construct( $rules ) {
        $this->rules = $rules ;
    }

    public function checkRules( $controllerName,$actionName ) {
        if( !isset( $this->rules['order'] ) ) {
            return true ;
        }
        else {
            $aceessResult = array(
                'allow' => true ,
                'deny'  => false ,
            );
            $returnResult = true ;
            foreach( explode(',',$this->rules['order']) as $r ) {
                $key = $r.'Rules';
                if( !isset($this->rules[$key]) ) {
                    continue ;
                }

                foreach( $this->rules[$key] as $line ) {
                    if( $line == '*' ) {
                        $returnResult = $returnResult&&$aceessResult[$r] ;
                        break ;
                    }
                    $temp = explode('/',trim($line));
                    if( empty($temp[0])||$controllerName!=$temp[0] ) {
                        continue ;
                    }
                    elseif( empty($temp[1])||$actionName!=$temp[1] ) {
                        continue ;
                    }
                    else {
                        $returnResult = $returnResult&&$aceessResult[$r] ;
                        break ;
                    }
                    $returnResult = $returnResult&&$aceessResult[$r] ;
                }
                //var_dump($returnResult);
            }
            return $returnResult ;
        }
    }
}

