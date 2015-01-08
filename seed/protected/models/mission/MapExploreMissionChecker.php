<?php
/**
 * MapExploreMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-08
 * @package Seed
 **/
class MapExploreMissionChecker extends MissionAccumulateChecker
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
            if ($deserveData[0]==$event->params['mapId']) {
                $currentCount = $this->setMissionProcess($event->playerId);
                $this->saveProcessCount($event->playerId, $currentCount);
                return $currentCount >= $deserveData[1];
            } else {
                return false;
            }
        } else {
            throw new CException(Yii::t('Mission', 'deserveParams does note exist.'));
        }
    }
}
?>
