<?php
class PowerController extends Controller
{
    /* 为自己的太阳能充能 */
    public function actionCharge()
    {
        $player = Yii::app()->objectLoader->load('Player',$this->playerId);
        $supplyPower = $player->getPlayerPoint('supplyPower');
        $supplyPower->restoreToMax();

        //全局消息
        GlobalMessage::setPowerWarning($this->playerId); 
        //新手引导
        if (Yii::app()->objectLoader->load('GuideModel',$this->playerId)->isCurrentGuide(80)) {
            $player->setStatus('guideLevel', 87);
            GuideModel::checkGuideState($this->playerId);
        }

        if ($this->actionType === REQUEST_TYPE_NATIVE_API) {
            $callback = array('refreshenergy'=>$supplyPower->getRemainTime(), 'refreshmaxenergy'=>$supplyPower->getMaxTime());
            $this->display('', array('callback'=>$callback));
        } else {
            $this->redirect($this->createUrl('player/getIndex'));
        }
    }

    /* 为好友的太阳能充能 */
    public function actionChargeFriend()
    {
        if(isset($_REQUEST['friendId'])) {
            //虚拟好友充能
            if (VisualFriend::isVisualFriend($this->playerId, $_REQUEST['friendId'])) {
                $isChargedToday = VisualFriend::isChargedToday($this->playerId);
                if (!$isChargedToday) {
                    //增加金钱
                    $increase = SUPPLYPOWER_FRIENDCHARGE;
                    $player = Yii::app()->objectLoader->load('Player', $this->playerId);
                    $player->addGold(floor(($player->level/2+3)*($increase/20)));

                    VisualFriend::setChargeCDTime($this->playerId, time());

                    $this->display(array('addGold'=>floor(($player->level/2+3)*($increase/20))));
                } else {
                    throw new SException(Yii::t('Friend', 'you have charged for this friend today'));
                }
            } else {
                $isChargedToday = PlayerFriend::isChargedToday($this->playerId, $_REQUEST['friendId']);
                if (!$isChargedToday) {
                    //充能
                    if (Yii::app()->objectLoader->load('GuideModel',$_REQUEST['friendId'])->isNewUserGuide()) {
                        $increase = SUPPLYPOWER_FRIENDCHARGE;
                    } else {
                        $friend = Yii::app()->objectLoader->load('Player',$_REQUEST['friendId']);
                        $supplyPower = $friend->getPlayerPoint('supplyPower');
                        $valueBeforeCharge = $supplyPower->getValue();
                        $increase = $supplyPower->addValue(SUPPLYPOWER_FRIENDCHARGE) - $valueBeforeCharge;
                    }
                    //增加金钱
                    $player = Yii::app()->objectLoader->load('Player', $this->playerId);
                    $player->addGold(floor(($player->level/2+3)*($increase/20)));

                    PlayerFriend::powerChange($this->playerId, $_REQUEST['friendId'], time());

                    //全局消息
                    GlobalMessage::setPowerWarning($this->playerId); 

                    if ($this->actionType === REQUEST_TYPE_NATIVE_API) {
                        $this->display(array('addGold'=>floor(($player->level/2+3)*($increase/20))));
                    } else {
                        $this->redirect($this->createUrl('player/getIndex'));
                    }
                } else {
                    throw new SException(Yii::t('Friend', 'you have charged for this friend today'));
                }
            }
        }
    }
}
?>
