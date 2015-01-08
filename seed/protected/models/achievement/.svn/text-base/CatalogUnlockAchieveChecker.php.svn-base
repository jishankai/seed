<?php
/**
 * CatalogUnlockAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-05
 * @package Seed
 **/
class CatalogUnlockAchieveChecker extends AchieveSimpleChecker
{
    private $_deserveParams;

    function __construct($achievementId, $deserveParams)
    {
        $this->_deserveParams = $deserveParams;
        parent::__construct($achievementId);
    }

    public function checkComplete($event)
    {
        $catalog = Yii::app()->objectLoader->load('Catalog', $event->playerId);
        $deserveData = explode(',', $this->_deserveParams);
        switch ($deserveData[0]) {
        case 'body':
            $count = $catalog->getBodyCountSum();
            break;
        case 'head':
            $count = $catalog->getBudCountSum();
            break;
        case 'deco':
            $count = $catalog->getDressCountSum();
            break;
        default:
            if ($event->params['bodyId'] == $deserveData[0]) {
                $count = $catalog->getBodyCount($deserveData[0]);
            }
            break;
        }
        if (!empty($count)) {
            $this->saveProcessCount($event->playerId, $count);
            if ($count>=$deserveData[1]) {
                return true;
            } else {
                return false;
            }
        } 
    }
}
?>
