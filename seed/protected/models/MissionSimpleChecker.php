<?php
/**
 * MissionSimpleChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-07
 * @package Seed
 **/
class MissionSimpleChecker extends MissionChecker
{
    public function checkComplete($event)
    {
        $this->saveProcessCount($event->playerId);
        return true;
    }
}
?>
