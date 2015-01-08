<?php
/**
 * SeedExploreMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-09
 * @package Seed
 **/
class SeedExploreMissionChecker extends MissionAccumulateChecker
{
    private $_deserveParams;

    function __construct($missionId, $deserveParams=0)
    {
        $this->_deserveParams = $deserveParams;
        parent::__construct($missionId);
    }

    public function checkComplete($event)
    {
        //if (!empty($this->_deserveParams)) {
            $deserveData = explode(',', $this->_deserveParams);
            if (empty($deserveData[1])) {
                if ($deserveData[0] == $event->params['seedId']) {
                    $this->saveProcessCount($event->playerId);
                    return true;
                } else {
                    return false;
                }
            } else {
                if($deserveData[0]==$event->params['mapId']) {
                    $currentCount = $this->setMissionProcess($event->playerId);
                    $this->saveProcessCount($event->playerId, $currentCount);
                    return $currentCount >= $deserveData[1];
                }
            }
        //} else {
        //    throw new CException(Yii::t('Mission', 'deserveParams does note exist.'));
        //}
    }
}
?>
