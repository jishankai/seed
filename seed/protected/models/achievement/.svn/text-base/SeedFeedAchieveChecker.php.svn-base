<?php
/**
 * SeedFeedAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-05
 * @package Seed
 **/
class SeedFeedAchieveChecker extends AchieveAccumulateChecker
{
    public function checkComplete($event)
    {
        $value = $event->params['itemCount'];
        $currentCount = $this->setAchieveProcess($event->playerId, $value); 
        $this->saveProcessCount($event->playerId, $currentCount);
        return $this->checkAchieveProcess($currentCount);
    }
}
?>
