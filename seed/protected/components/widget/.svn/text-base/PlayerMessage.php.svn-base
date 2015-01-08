<?php
/**
 * PlayerPointMessage
 **/
class PlayerMessage extends CWidget
{
    public function run()
    {
        $session = Yii::app()->session;
        $playerId = $session['playerId'];
        $player = Yii::app()->objectLoader->load('Player', $playerId);

        $level = $player->level;
        $gold = $player->gold;
        $exp = $level<100 ? $player->exp : 'MAX';
        $expMax = $level<100 ? $player->nextLevelExp() : 'MAX'; 
        $userMoney = $player->getUserMoney();

        $actionPoint = $player->getPlayerPoint('actionPoint');
        $actionPointValue = $actionPoint->getValue();
        $actionPointMaxValue = $actionPoint->getMax();
        $actionPointTime = $actionPoint->getRemainTime();
        
        $this->render('PlayerMessage', array(
            'level' => $level,
            'gold' => $gold,
            'exp' => $exp,
            'expMax' => $expMax,
            'userMoney' => $userMoney,
            'actionPoint' => $actionPointValue,
            'actionPointMax' => $actionPointMaxValue,
            'actionPointTime' => $actionPointTime,
            )
        );
    }
}
?>
