<?php
/**
 * CatalogUnlockMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-08
 * @package Seed
 **/
class CatalogUnlockMissionChecker extends MissionChecker
{
    private $_deserveParams;

    function __construct($missionId, $deserveParams)
    {
        $this->_deserveParams = $deserveParams;
        parent::__construct($missionId);
    }

    public function checkComplete($event)
    {
        $catalog = Yii::app()->objectLoader->load('Catalog', $event->playerId);
        $count = $catalog->getCatalogCount();
        $this->saveProcessCount($event->playerId, $count);
        if ($count>=$this->_deserveParams) {
            return true;
        } else {
            return false;
        }
    }
}
?>
