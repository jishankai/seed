<?php

class SiteController extends Controller
{
    /*
    public function filters() {
        }
    */
    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */

    public function actionError(){
        if($error=Yii::app()->errorHandler->error){
            header("HTTP/1.0 200 ");
            if( !isset($error['errorCode'])||$error['errorCode']>=0 )  $error['errorCode'] = -1 ;
            if($error['type'] != 'SException'){
                error_log($error['message']);
                if( SeedConfig::isDebug() ) {
                    $error['message'] .= "\n".$error['trace'] ;
                }
                else {
                    $error['message'] = Yii::t('View', 'system error');
                }
                $params = array();
            }
            else {
                $params = SException::getParams( $error['errorCode'] );
            }

            if( $this->actionType!=REQUEST_TYPE_NORMAL ){
                $this->error($error['message'], $error['errorCode'],$params);
            }else{
                $session = Yii::app()->session;
                $session->open();
                $error['userId'] = '';
                if(isset($session['userId'])){
                    $error['userId'] = $session['userId'];
                }
                
                $this->render('/error/html', $error);
            }
        }
    }

    public function actionBlank() {

    }

}