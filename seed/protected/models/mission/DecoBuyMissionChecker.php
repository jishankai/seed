<?php
/**
 * DecoBuyMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-09
 * @package Seed
 **/
class DecoBuyMissionChecker extends MissionChecker
{
    private $_deserveParams;

    function __construct($missionId, $deserveParams=null)
    {
        $this->_deserveParams = $deserveParams;
        parent::__construct($missionId);
    }

    public function checkComplete($event)
    {
        if (!empty($event->params['itemId'])) {
            if ($this->_deserveParams==$event->params['itemId']) {
                $this->saveProcessCount($event->playerId);
                return true;
            }
        }
        return false;
    }
}
?>
