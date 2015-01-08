<?php
/**
 * FriendVisitMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-09
 * @package Seed
 **/
class FriendVisitMissionChecker extends MissionGatherChecker
{
    private $_deserveParams;

    function __construct($missionId, $deserveParams)
    {
        $this->_deserveParams = $deserveParams;
        parent::__construct($missionId);
    }

    public function checkComplete($event)
    {
        if (!empty($this->_deserveParams)) {
            $deserveData = explode(',', $this->_deserveParams);

            $missionId = $this->getMissionId();
            $id = MissionRecord::findId($event->playerId, $missionId);
            $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
            $currentProcess = $missionRecord->getGatherProcess();
            $currentProcess[$event->params['behavior']][] = $event->params['friendId'];
            $missionRecord->setGatherProcess($currentProcess);

            $friendCount = 0;
            $visitCount = 0;
            if (!empty($currentProcess['friend'])) {
                $friendCount = min($deserveData[0],count(array_unique($currentProcess['friend'])));
            }
            if (!empty($currentProcess['visit'])) {
                $visitCount = min($deserveData[1],count(array_unique($currentProcess['visit'])));
            }
            $this->saveProcessCount($event->playerId, ($friendCount+$visitCount));

            if (!empty($friendCount) and !empty($visitCount)) {
                return $friendCount>=$deserveData[0] && $visitCount>=$deserveData[1];
            } else {
                return false;
            }
        } else {
            throw new CException(Yii::t('Mission', 'deserveParams does note exist.'));
        }
    }
}
?>
