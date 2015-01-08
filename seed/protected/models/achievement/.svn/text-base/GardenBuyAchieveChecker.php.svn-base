<?php
/**
 * GardenBuyAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-07
 * @package Seed
 **/
class GardenBuyAchieveChecker extends AchieveAccumulateChecker
{
    public function checkComplete($event)
    {
        if (isset($event->params['value'])) {
            $currentCount = $this->setAchieveProcess($event->playerId, $event->params['value']);
        } else {
            $currentCount = $this->setAchieveProcess($event->playerId);
        }
        $this->saveProcessCount($event->playerId, $currentCount+1);
        return $this->checkAchieveProcess($currentCount);
    }
}
?>
