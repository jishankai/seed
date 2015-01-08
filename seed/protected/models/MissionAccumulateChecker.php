<?php
/**
 * MissionAccumulateChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-07
 * @package Seed
 **/
class MissionAccumulateChecker extends MissionChecker
{
    private $_deserveCount;

    function __construct($missionId, $deserveCount=0)
    {
        $this->_deserveCount = $deserveCount;
        parent::__construct($missionId);
    }

    protected function setMissionProcess($playerId, $value=1)
    {
        $id = MissionRecord::findId($playerId, $this->missionId);
        $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
        $currentCount = $missionRecord->getAccumulateProcess();

        $currentCount = intval($currentCount) + $value;
        $missionRecord->setAccumulateProcess($currentCount);
        
        return $currentCount;
    }

    protected function checkMissionProcess($currentCount)
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
            $currentCount = $this->setMissionProcess($event->playerId, $event->params['value']);
        } else {
            $currentCount = $this->setMissionProcess($event->playerId);
        }
        $this->saveProcessCount($event->playerId, $currentCount);
        return $this->checkMissionProcess($currentCount);
    }
}
?>
