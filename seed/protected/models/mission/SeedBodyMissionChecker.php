<?php
/**
 * SeedBodyMissionChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-09
 * @package Seed
 **/
class SeedBodyMissionChecker extends MissionChecker
{
    private $_deserveParam;

    function __construct($missionId, $deserveParam)
    {
        $this->_deserveParam = $deserveParam;
        parent::__construct($missionId);
    }

    public function checkComplete($event)
    {
        if (!empty($this->_deserveParam)) {
            if (empty($event->params['seedId'])) {
                return Yii::app()->objectLoader->load('SeedModel',$event->playerId)->checkSeedExists($this->_deserveParam); 
            }
            $seedId = $event->params['seedId'];
            $seed = Yii::app()->objectLoader->load('Seed', $seedId);

            if ($this->_deserveParam==$seed->bodyId) {
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
