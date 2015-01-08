<?php
/**
 * CupAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-05
 * @package Seed
 **/
class CupAchieveChecker extends AchieveSimpleChecker
{
    private $_deserveType;

    public function __construct($achievementId, $deserveParams)
    {
        $this->_deserveType = $deserveParams;
        parent::__construct($achievementId);
    }

    public function checkComplete($event)
    {
        $cupId = $event->params['decoId'];
        if ($cupId>=7000) {
         switch (($cupId-7000)%3) {
             case 0:
                 if ($this->_deserveType=='copper') {
                     return true;
                 }
                 break;
             case 1:
                 if ($this->_deserveType=='silver') {
                     return true;
                 }
                 break;
             case 2:
                 if ($this->_deserveType=='gold') {
                     return true;
                 }
                 break;
             default:
                 break;
         }
        }
         return false;
    }
}
?>
