<?php
/**
 * ExpReward
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-02-27
 * @package Seed
 **/
class ExpReward extends Reward
{
    function __construct($value)
    {
        parent::__construct('exp', $value);
    }

    public function reward($playerId)
    {
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        $player->addExp($this->getValue());
    }
}
?>
