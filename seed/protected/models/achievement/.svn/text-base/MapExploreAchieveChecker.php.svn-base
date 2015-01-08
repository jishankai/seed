<?php
/**
 * MapExploreAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-05
 * @package Seed
 **/
class MapExploreAchieveChecker extends AchieveGatherChecker
{
    private $_deserveParams;

    function __construct($achievementId, $deserveParams=0)
    {
        if (!empty($deserveParams)) {
            $this->_deserveParams = explode(',', $deserveParams);
        }
        parent::__construct($achievementId);
    }

    public function checkComplete($event)
    {
        //$currentProcess = $this->_setAchieveProcess($event->playerId);
        $currentProcess = $event->process;
        if (empty($this->_deserveParams)) {
            return WorldMap::checkExploreAll($currentProcess);
        } else {
            return $this->_checkAchieveProcess($event->params['mapId'], $event->playerId);
        }
    }
    
    protected function _checkAchieveProcess($currentProcess, $playerId)
    {
        if ($this->_deserveParams[0] == $currentProcess) {
            $achievementId = $this->achievementId;
            $id = AchievementRecord::findId($playerId, $achievementId);
            $achievementRecord = Yii::app()->objectLoader->load('AchievementRecord', $id);
            $currentCount = $achievementRecord->processCount;
           
            $currentTimes = $currentCount+1;
            $this->saveProcessCount($playerId, $currentTimes); 
            return $currentTimes>=$this->_deserveParams[1];
        } else {
            return false;
        }

    }

}
?>
