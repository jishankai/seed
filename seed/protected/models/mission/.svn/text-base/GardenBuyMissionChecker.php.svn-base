<?php
/**
 * GardenBuyMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-08
 * @package Seed
 **/
class GardenBuyMissionChecker extends MissionChecker
{
    private $_deserveParams;

    function __construct($missionId, $deserveParams=1)
    {
        $this->_deserveParams = $deserveParams;
        parent::__construct($missionId);
    }

    public function checkComplete($event)
    {
        $player = Yii::app()->objectLoader->load('Player', $event->playerId);
        $count = $player->gardenNum; 
        if ($count>$this->_deserveParams) {
            return true;
        } else {
            return false;
        }
    }
}
?>
