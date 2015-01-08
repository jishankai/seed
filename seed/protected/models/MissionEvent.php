<?php
/**
 * MissionEvent
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-02-24
 * @package Seed
 **/

class MissionEvent extends SEvent
{
    private $_ids;

    function __construct($playerId, $eventId, $params=array()) {
        $this->_ids = array();

        $uncompletedMissionIds = MissionModel::getUncompletedMissionIds($playerId);
        $allMissionIds = MissionModel::getMissionIdsByEvent($eventId);

        $missionIds = array_intersect($uncompletedMissionIds, $allMissionIds);
        foreach ($missionIds as $missionId) {
            $id = MissionRecord::findId($playerId, $missionId);
            if (!$id) {
                continue;
            }
            $this->_ids[] = $id;
        }
        parent::__construct($playerId, $eventId, $params);
    }

    public function onMissionComplete()
    {
        foreach ($this->_ids as $id) {
            $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
            if ($missionRecord->status==MISSIONRECORD_UNCOMPLETED or $missionRecord->status==MISSIONRECORD_NEW) {
                $mission = Yii::app()->objectLoader->load('Mission', $missionRecord->missionId);
                if ($mission->checkComplete($this)) {
                    $missionRecord->complete();
                }
            }
        }       
    }

    public function saveProcess($value)
    {
        foreach ($this->_ids as $id) {
            $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
            $process = $missionRecord->getGatherProcess();
            $process[] = $value;
            $missionRecord->setGatherProcess($process);
        }
    }
}
?>
