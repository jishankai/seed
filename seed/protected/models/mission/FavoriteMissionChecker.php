<?php
/**
 * FavoriteMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-09
 * @package Seed
 **/
class FavoriteMissionChecker extends MissionGatherChecker
{
    public function checkComplete($event)
    {
        $missionId = $this->getMissionId();
        $id = MissionRecord::findId($event->playerId, $missionId);
        $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
        $currentProcess = $missionRecord->getGatherProcess();
        if (empty($currentProcess[$event->params['behavior']])) {
            $currentProcess[$event->params['behavior']] = 1;
        }
        $missionRecord->setGatherProcess($currentProcess);

        if (!empty($currentProcess['favoriteGarden'])&&!empty($currentProcess['favoriteSeed'])) {
            $this->saveProcessCount($event->playerId);
            return true;
        } else {
            return false;
        }
    }
}
?>
