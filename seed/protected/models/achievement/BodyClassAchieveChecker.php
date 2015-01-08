<?php
/**
 * BodyClassAchieveChecker
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-05
 * @package Seed
 **/
class BodyClassAchieveChecker extends AchieveSimpleChecker
{
    private $_deserveCount;

    function __construct($achievementId, $deserveCount)
    {
        $this->_deserveCount = $deserveCount;
        parent::__construct($achievementId);
    }

    public function checkComplete($event)
    {
        $diffMaxCount = $this->getBodyDiffMax($event->playerId); 
        $this->saveProcessCount($event->playerId, $diffMaxCount);
        if ($diffMaxCount>=$this->_deserveCount) {
            return true;
        } else {
            return false;
        }
        
    }

    private function getBodyDiffMax($playerId)
    {
        $gardenModel = Yii::app()->objectLoader->load('GardenModel',$playerId);
        $seedModel = Yii::app()->objectLoader->load('SeedModel',$playerId);
        $bodyCount = array();
        foreach( $gardenModel->getGardenObject() as $garden ) {
            foreach( $seedModel->getGardenSeeds( $garden->gardenId ) as $seed ) {
                $key = $seed->bodyId ;
                /** 如果是判断相同的种子 **/
                /** $key = "{$seed->bodyId}_{$seed->faceId}_{$seed->budId}";  **/
                if( isset($bodyCount[$garden->gardenId][$key]) ) {
                    $bodyCount[$garden->gardenId][$key] ++ ;
                }
                else {
                    $bodyCount[$garden->gardenId][$key] = 1 ;
                }
            }
        }
        $maxCount = 0;
        foreach ($bodyCount as $countByGarden) {
            $maxCount = max($maxCount, count($countByGarden));
        }

        return $maxCount;
    }
}
?>
