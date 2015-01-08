<?php
class GameController extends Controller {

    public function actionDo( $action,$value,$salt,$sign ) {
        if( isset($_REQUEST['combo']) ) {
            $combo = $_REQUEST['combo'];
            $checkSign = hash('sha256', $action.'|'.$value.'|'.$combo.'|'.$salt.'|'.GAME_CHECK_KEY );
        }
        else {
            $checkSign = hash('sha256', $action.'|'.$value.'|'.$salt.'|'.GAME_CHECK_KEY );
        }
        if( $checkSign!=$sign ) {
            throw new CException('error params');
        }
        $session = Yii::app()->session ;
        $result = array();
        $guideModel = Yii::app()->objectLoader->load('GuideModel',$this->playerId);
        $isCurrentGuide = $guideModel->isCurrentGuide(70) ;
        if( $action!='start' ) {
            if(!empty($session['InGameSeedId'])) {
                $seed = Yii::app()->objectLoader->load('Seed',$session['InGameSeedId']);
            }
            else {
                throw new CException('not select seed');
            }

            switch( $action ) {
                case 'over' : 
                    $session['InGameSeedId'] = 0 ;
                    $seed->setGrowValue( $value );
                    break ;
                case 'award' :
                    $itemMeta = Yii::app()->objectLoader->load('ItemMeta',47);
                    $item = Yii::app()->objectLoader->load('Item',$this->playerId);
                    $item -> addItem( $itemMeta,'game seed['.$seed->seedId.']',$value );
                    break ;
                case 'growValue' : 
                    $seed->setGrowValue( $value );
                    break ;
                default :
                    throw new CException('action error');
            }
            if( !empty($combo) ) {
                AchievementModel::checkComboAchievement($combo, $this->playerId);
            }

            if( !$session['seedGrownChecked'] && $seed->isGrown() ){
                if( $isCurrentGuide ) {
                    $guideModel->saveAccessLevel(76);
                }
                //小游戏任务检查
                $missionEvent = new MissionEvent($this->playerId, MISSIONEVENT_MINIGAME);
                $missionEvent->onMissionComplete();

                $session['seedGrownChecked'] = true ;
            } 
        }
        else {
            // start game actions
            $session['InGameSeedId'] = $value ;
            $seed = Yii::app()->objectLoader->load('Seed',$session['InGameSeedId']);
            $seed->checkOwner( $this->playerId );
            $session['seedGrownChecked'] = $seed->isGrown() ;
            if($isCurrentGuide) {
                GlobalState::set($this->playerId,'USER_GUIDE_LEVEL',74);
            }
        }
        if( $seed->isExists() ) {
            $result = array(
                'seedId'    => $seed->seedId ,
                'growValue' => $seed->getGrowValue() ,
                'maxGrowValue'  => $seed->getMaxGrowValue() ,
                'price'         => $seed->getPrice() ,
                'maxPrice'      => $seed->getMaxPrice() ,
                'growPeriod'    => $seed->growPeriod ,
                'parts'         => $seed->getNativeParts() ,
            );
        }
        $this->display( $result );
    }
}
