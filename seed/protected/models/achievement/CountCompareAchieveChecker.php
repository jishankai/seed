<?php
/**
 * CountCompareAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-05
 * @package Seed
 **/
class CountCompareAchieveChecker extends AchieveSimpleChecker
{
    private $_deserveCount;

    function __construct($achievementId, $deserveCount)
    {
        $this->_deserveCount = $deserveCount;
        parent::__construct($achievementId);
    }

    public function checkComplete($event)
    {
        $this->saveProcessCount($event->playerId, $event->params['count']);
        if ($event->params['count']>=$this->_deserveCount) {
            return true;
        } else {
            return false;
        }
        
    }
}
?>
