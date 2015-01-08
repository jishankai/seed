<?php
/**
 * MissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-07
 * @package Seed
 **/
class MissionChecker extends CModelBehavior
{
    private $_missionId;

    function __construct($missionId)
    {
        $this->_missionId = $missionId;
    }

    public function getMissionId()
    {
        return $this->_missionId;
    }

    public function checkComplete($event) {}

    public function saveProcessCount($playerId, $count=1)
    {
        $missionId = $this->missionId;
        $id = MissionRecord::findId($playerId, $missionId);
        $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
        $missionRecord->processCount = $count;
        $missionRecord->saveAttributes(array('processCount'));
    }
} 
?>
