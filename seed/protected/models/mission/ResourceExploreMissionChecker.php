<?php
/**
 * ResourceExploreMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-09
 * @package Seed
 **/
class ResourceExploreMissionChecker extends MissionGatherChecker
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
            $currentCount = 0;
            $deserveParams = explode(';', $this->_deserveParams);

            $missionId = $this->getMissionId();
            $id = MissionRecord::findId($event->playerId, $missionId);
            $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);

            foreach ($deserveParams as $deserveParam) {
                $deserveData = explode(',', $deserveParam);
                if (count($deserveData)==1) {
                    $specialItems = array('5', '10', '15', '20', '25', '30');
                    $currentProcess = $missionRecord->getAccumulateProcess();
                    if (in_array($event->params['itemId'], $specialItems)) {
                        $currentProcess += $event->params['itemCount']; 
                    }
                    $missionRecord->setAccumulateProcess($currentProcess);
                    $this->saveProcessCount($event->playerId, $currentProcess);

                    return $currentProcess >= $deserveData[0];
                } else {
                    $currentProcess = $missionRecord->getGatherProcess();
                    if ($deserveData[0]==$event->params['mapId'] && $deserveData[1]==$event->params['itemId']) {
                        if (!empty($currentProcess[$deserveData[1]])) {
                            $currentProcess[$deserveData[1]] = min(++$currentProcess[$deserveData[1]], $deserveData[2]); 
                        } else {
                            $currentProcess[$deserveData[1]] = 1; 
                        }
                        $missionRecord->setGatherProcess($currentProcess);
                    }
                    if (!empty($currentProcess[$deserveData[1]])) {
                        $currentCount += $currentProcess[$deserveData[1]];
                    }
                }
            }
            if (!empty($currentCount)) {
                $this->saveProcessCount($event->playerId, $currentCount);
            }
            
            foreach ($deserveParams as $deserveParam) {
                $deserveData = explode(',', $deserveParam);
                $currentProcess = $missionRecord->getGatherProcess();
                if (empty($currentProcess[$deserveData[1]]) or $currentProcess[$deserveData[1]]<$deserveData[2]) {
                    return false;
                }
            }
            return true;
        } else {
            throw new CException(Yii::t('Mission', 'deserveParams does note exist.'));
        }
    }
}
?>
