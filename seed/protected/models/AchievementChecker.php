<?php
/**
 * AchievementChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-07
 * @package Seed
 **/
class AchievementChecker extends CModelBehavior
{
    private $_achievementId;

    function __construct($achievementId)
    {
        $this->_achievementId = $achievementId;
    }

    public function getAchievementId()
    {
        return $this->_achievementId;
    }

    public function checkComplete($event) {}

    public function saveProcessCount($playerId, $count=1)
    {
        $achievementId = $this->achievementId;
        $id = AchievementRecord::findId($playerId, $achievementId);
        $achievementRecord = Yii::app()->objectLoader->load('AchievementRecord', $id);
        if ($achievementRecord->processCount < $count) {
            $achievementRecord->processCount = $count;
            $achievementRecord->saveAttributes(array('processCount'));
        }
    }
}
?>
