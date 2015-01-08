<?php
/**
 * SeedPlantMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-09
 * @package Seed
 **/
class SeedPlantMissionChecker extends MissionChecker
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
                $currentCount = GardenModel::maxSeedGardenCount($event->playerId);
                $this->saveProcessCount($event->playerId, $currentCount);

                if ($currentCount>=$this->_deserveParams) {
                    return true;
                } else {
                    return false;
                }

        } else {
            throw new CException(Yii::t('Mission', 'deserveParams does note exist.'));
        }
    }
}
?>
