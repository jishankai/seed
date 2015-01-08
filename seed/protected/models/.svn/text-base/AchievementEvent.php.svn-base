<?php
/**
 * AchievementEvent
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-01
 * @package Seed
 **/

class AchievementEvent extends SEvent
{

    public $process;
    private $_ids;

    function __construct($playerId, $eventId, $params=array()) {
        $this->_ids = array();

        $achievementIds = AchievementModel::getAchievementIdsByEvent($eventId);

        foreach ($achievementIds as $achievementId) {
            $this->_ids[]  = AchievementRecord::findId($playerId, $achievementId);
        }
        parent::__construct($playerId, $eventId, $params);
    } 

    public function onAchieveComplete()
    {
        if (!isset($this->playerId) or $this->playerId<=0) {
            return ;
        }
        $achievementCountByClass = 0;
        foreach ($this->_ids as $id) {
            $achievementRecord = Yii::app()->objectLoader->load('AchievementRecord', $id);

            if ($achievementRecord->status == ACHIEVEMENTRECORD_UNCOMPLETED) {
                $achievement = Yii::app()->objectLoader->load('Achievement', $achievementRecord->achievementId);
                if ($achievement->checkComplete($this)) {
                    $achievementRecord->complete();

                }  
            }         
            $deserveData = explode(',', $achievement->expectedParams);
            if (!empty($this->params['bodyId']) && $this->params['bodyId']==$deserveData[0]) {
                $achievementCountByClass++;
                if ($achievementCountByClass==3) {
                    break;
                }
            }
        }
    }

    public function saveProcess($value)
    {
        $achievementIds = AchievementModel::getAchievementIdsByEvent($this->eventId);
        $maxAchievementId = 0;
        foreach ($achievementIds as $achievementId) {
            if ($achievementId<$maxAchievementId) {
                continue;
            }
            $maxAchievementId = $achievementId;
        }
        $id = AchievementRecord::findId($this->playerId, $maxAchievementId);

        $achievementRecord = Yii::app()->objectLoader->load('AchievementRecord', $id);
        if ($achievementRecord->status!=ACHIEVEMENTRECORD_COMPLETED) {
            $process = $achievementRecord->getGatherProcess();
            if (is_array($value)) {
                foreach ($value as $category=>$num) {
                    if (isset($process[$category])) {
                        $process[$category] += $num;
                    } else {
                        $process[$category] = $num;
                    }
                }
            } else {
                $process[] = $value;
                $process = array_unique($process);
            }

            $achievementRecord->setGatherProcess($process);
            $this->process = $process;
        }

    }

}
?>
