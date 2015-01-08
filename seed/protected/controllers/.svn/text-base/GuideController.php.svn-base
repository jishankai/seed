<?php
class GuideController extends Controller{

    public function actionIndex() {
        $this->showGlobalMessage = false ;
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $guide = Yii::app()->objectLoader->load('GuideModel',$this->playerId);
        
        if( $guideInfo = $guide->getGuideInfo() ) {
            if( $guideInfo['type']=='path' ) {
                //$this->redirect( $this->createUrl($guideInfo['path']) );
                //$this->display($guideInfo['index']);
            }
            else {
                $currentAccessLevel = max( isset($_REQUEST['accessLevel'])?$_REQUEST['accessLevel']:0,$player->getStatus('guideLevel'),$guideInfo['index']) ;
                $params = array(
                    'guideInfo' => $guideInfo ,
                    'player'    => $player ,
                    'currentAccessLevel'=> $currentAccessLevel ,
                    'dialogMessage'     => Yii::t('GuideMessage','message_'.$currentAccessLevel),
                    'showType'  => isset($_REQUEST['showType'])?intval($_REQUEST['showType']):1,
                );
                $this->display( $guideInfo['template'],$params );
            }
        }
        else {
            GlobalState::set($this->playerId,'NATIVE_CLOSE','close');
            $params = array();
            $this->display('noGuide',$params);
        }
    }
    
    public function actionFriend() {
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $guide = Yii::app()->objectLoader->load('GuideModel',$this->playerId);
        $currentAccessLevel = isset($_REQUEST['accessLevel'])?intval($_REQUEST['accessLevel']):103 ;
        $params = array(
            'currentAccessLevel' => $currentAccessLevel,
        );
        $this->display( 'friend', $params );
    }

    public function actionSaveStatus() {
        $accessLevel = isset($_REQUEST['accessLevel'])?intval($_REQUEST['accessLevel']):0 ;
        $guideModel = Yii::app()->objectLoader->load('GuideModel',$this->playerId);
        try{
            $transaction = Yii::app()->db->beginTransaction() ;
            $guideModel->saveStatus($accessLevel);
            if( empty($_REQUEST['noState']) ) {
                GuideModel::checkGuideState( $this->playerId );
            }
            $transaction -> commit();
        } catch (Exception $e) {
            $transaction -> rollBack();
            throw $e ;
        }
        $this->display();
    }
}
