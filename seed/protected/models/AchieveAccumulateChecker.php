<?php
/**
 * AchieveAccumulateChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-07
 * @package Seed
 **/
class AchieveAccumulateChecker extends AchievementChecker
{
    private $_deserveCount;

    function __construct($achievementId, $deserveCount)
    {
        $this->_deserveCount = $deserveCount;
        parent::__construct($achievementId);
    }

    protected function setAchieveProcess($playerId, $value=1)
    {
        $achievementId = $this->achievementId;
        $id = AchievementRecord::findId($playerId, $achievementId);
        $achievementRecord = Yii::app()->objectLoader->load('AchievementRecord', $id);
        $currentCount = $achievementRecord->getAccumulateProcess();

        $currentCount = intval($currentCount) + $value;
        $achievementRecord->setAccumulateProcess($currentCount);
        
        return $currentCount;
    }

    protected function checkAchieveProcess($currentCount)
    {
        if ($currentCount<$this->_deserveCount) {
            return false;
        } else {
            return true;
        }
        
    }

    public function checkComplete($event)
    {
        if (isset($event->params['value'])) {
            $currentCount = $this->setAchieveProcess($event->playerId, $event->params['value']);
        } else {
            $currentCount = $this->setAchieveProcess($event->playerId);
        }
        $this->saveProcessCount($event->playerId, $currentCount);
        return $this->checkAchieveProcess($currentCount);
    }
}
?>
