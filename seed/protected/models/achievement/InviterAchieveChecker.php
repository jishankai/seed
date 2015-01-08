<?php
/**
 * InviterAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-05
 * @package Seed
 **/
class InviterAchieveChecker extends AchieveSimpleChecker
{
    private $_deserveCount;

    function __construct($achievementId, $deserveCount)
    {
        $this->_deserveCount = $deserveCount;
        parent::__construct($achievementId);
    }

    public function checkComplete($event)
    {
        $command = Yii::app()->db->createCommand('SELECT COUNT(*) FROM player WHERE inviterId = :inviterId');
        $count = $command->bindValue(':inviterId', $event->params['inviterId'])->queryScalar();
        $this->saveProcessCount($event->playerId, $count);
        if ($count>=$this->_deserveCount) {
            return true;
        } else {
            return false;
        }
        
    }
}
?>
