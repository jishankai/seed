<?php
/**
 * AchievementModel
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-01
 * @package Seed
 **/

class AchievementModel extends Model
{
    public static function getAchievementsByEvent($event)
    {
        $command = Yii::app()->db->createCommand('SELECT achievementId FROM achievement WHERE event = :event');
        $achievementIds = $command->bindValue(':event', $event)->queryColumn();

        return Achievement::multiLoad(array('achievementIds'=>$achievementIds), false);
    }

    public static function getAchievementIdsByEvent($event)
    {
        $command = Yii::app()->db->createCommand('SELECT achievementId FROM achievement WHERE event = :event');
        return $command->bindValue(':event', $event)->queryColumn();
    }

    public static function getAchievementRecords($playerId)
    {
        $command = Yii::app()->db->createCommand('SELECT id FROM achievementRecord WHERE playerId = :playerId');
        $ids = $command->bindValue(':playerId', $playerId)->queryColumn();

        if (!empty($ids)) {
            return AchievementRecord::multiLoad(array('ids'=>$ids));
        } else {
            return array();
        }
        
    }

    public static function getUncheckAchievementByCategory($playerId, $category)
    {
        $command = Yii::app()->db->createCommand('SELECT achievementId FROM achievementRecord WHERE playerId = :playerId');
        $achieveIdsInRecord = $command->bindValue(':playerId', $playerId)->queryColumn();
        $command = Yii::app()->db->createCommand('SELECT achievementId FROM achievement WHERE category = :category GROUP BY class');
        $achieveIds = $command->bindValue(':category', $category)->queryColumn();

        $achievementIds = array_diff($achieveIds, $achieveIdsInRecord);
        if (!empty($achievementIds)) {
            return Achievement::multiLoad(array('achievementIds'=>$achievementIds));
        } else {
            return array();
        }
       
    }

    public static function checkComboAchievement($comboString, $playerId)
    {
        if (empty($comboString)) {
            return ;
        }

        $comboResult = explode(',', $comboString);
        foreach ($comboResult as $comboCell) {
            $combo = explode('_', $comboCell);
            $comboType = $combo[0];
            $comboTimes = $combo[1];
            switch ($comboType) {
                case '3':
                    //获得新绿之叶成就
                    $achieveEvent = new AchievementEvent($playerId, ACHIEVEEVENT_3COMBO, array('value'=>$comboTimes));
                    $achieveEvent->onAchieveComplete();
                    break;
                case '5':
                    //获得新绿之叶成就
                    $achieveEvent = new AchievementEvent($playerId, ACHIEVEEVENT_5COMBO, array('value'=>$comboTimes));
                    $achieveEvent->onAchieveComplete();
                    break;
                case '6':
                    //获得新绿之叶成就
                    $achieveEvent = new AchievementEvent($playerId, ACHIEVEEVENT_6COMBO, array('value'=>$comboTimes));
                    $achieveEvent->onAchieveComplete();
                    break;
                
                default:
                    // code...
                    break;
            }
        }
        return ;
    }
}
?>
