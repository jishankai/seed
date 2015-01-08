<?php
/**
 * AchieveSimpleChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-07
 * @package Seed
 **/
class AchieveSimpleChecker extends AchievementChecker
{
    public function checkComplete($event)
    {
        $this->saveProcessCount($event->playerId);
        return true;
    }
}
?>
