<?php
/**
 * StoreFullAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-05
 * @package Seed
 **/
class StoreFullAchieveChecker extends AchieveSimpleChecker
{
    public function checkComplete($event)
    {
        $item = Yii::app()->objectLoader->load('Item', $event->playerId);
        if ($item->isPileFull()) {
            $this->saveProcessCount($event->playerId);
            return true;
        } else {
            return false;
        }

    }
}
?>
