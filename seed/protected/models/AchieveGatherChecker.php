<?php
/**
 * AchieveGatherChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-07
 * @package Seed
 **/
class AchieveGatherChecker extends AchievementChecker
{
    protected function _setAchieveProcess($playerId)
    {
        $achievementId = $this->getAchievementId();
        $id = AchievementRecord::findId($playerId, $achievementId);
        $achievementRecord = Yii::app()->objectLoader->load('AchievementRecord', $id);
        $currentProcess = $achievementRecord->getGatherProcess();

        return $currentProcess;        
    }

    protected function _checkAchieveProcess($currentProcess, $playerId) {}
}
?>
