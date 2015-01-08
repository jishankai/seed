<?php
/**
 * SeedRandomMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-09
 * @package Seed
 **/
class SeedRandomMissionChecker extends MissionChecker
{
    private $_deserveParams;

    function __construct($missionId, $deserveParams=0)
    {
        $this->_deserveParams = $deserveParams;
        parent::__construct($missionId);
    }

    public function checkComplete($event)
    {
        $missionId = $this->getMissionId();
        $playerId = $event->playerId;
        $id = MissionRecord::findId($playerId, $missionId);
        $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
        if (!empty($missionRecord->process)) {
            $deserveData = explode(',', $missionRecord->process);
            $deserveBodyId = $deserveData[0];
            $deserveFaceId = $deserveData[1];
            $deserveBudId = $deserveData[2];
             if (empty($event->params['seedId'])) {
                return Yii::app()->objectLoader->load('SeedModel',$event->playerId)->checkSeedExists($deserveBodyId, $deserveFaceId, $deserveBudId);     
            }
            $seed = Yii::app()->objectLoader->load('Seed', $event->params['seedId']) ;
            if ($seed->bodyId==$deserveBodyId && $seed->faceId==$deserveFaceId && $seed->budId==$deserveBudId) {
                $this->saveProcessCount($event->playerId);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
?>
