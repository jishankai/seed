<?php
/**
 * AchieveCountAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-08
 * @package Seed
 **/
class AchieveCountAchieveChecker extends AchieveSimpleChecker
{
    private $_deserveCount;

    function __construct($achievementId, $deserveCount)
    {
        $this->_deserveCount = $deserveCount;
        parent::__construct($achievementId);
    }

    public function checkComplete($event)
    {
        $achieveCompletedCount = AchievementRecord::getCompletedCount($event->playerId);
        
        $this->saveProcessCount($event->playerId, $achieveCompletedCount);
        if ($achieveCompletedCount>=$this->_deserveCount) {
            return true;
        } else {
            return false;
        }
        
    }
}
?>
