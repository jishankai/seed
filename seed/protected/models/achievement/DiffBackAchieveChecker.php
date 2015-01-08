<?php
/**
 * DiffBackAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-05
 * @package Seed
 **/
class DiffBackAchieveChecker extends AchieveSimpleChecker
{
    public function checkComplete($event)
    {
        if (GardenModel::isBackgroundGetEightType($event->playerId)) {
            $this->saveProcessCount($event->playerId);
            return true;
        } else {
            return false;
        }

    }
}
?>
