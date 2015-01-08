<?php
/**
 * MissionModel
 **/
class MissionModel extends Model
{
    public static function getUnconfirmRecordCount($playerId) {
        $command = Yii::app()->db->createCommand('SELECT COUNT(*) FROM missionRecord WHERE playerId = :playerId AND (status = '.MISSIONRECORD_UNCOMPLETED.' OR status = '.MISSIONRECORD_NEW.' OR status = '.MISSIONRECORD_COMPLETED.') GROUP BY playerId');
        return $command->bindValue(':playerId', $playerId)->queryScalar();
    }

    public static function getMissionIdsByEvent($event)
    {
        $command = Yii::app()->db->createCommand('SELECT missionId FROM mission WHERE event = :event');
        return $command->bindValue(':event', $event)->queryColumn();
    }

    public static function getUncompletedMissionIds($playerId) {
        $command = Yii::app()->db->createCommand('SELECT missionId FROM missionRecord WHERE playerId = :playerId AND (status = '.MISSIONRECORD_UNCOMPLETED.' OR status = '.MISSIONRECORD_NEW.')');
        return $command->bindValue(':playerId', $playerId)->queryColumn();
    }
    public static function getMissionsByEvent($event)
    {
        $command = Yii::app()->db->createCommand('SELECT missionId FROM mission WHERE event = :event');
        $missionIds = $command->bindValue(':event', $event)->queryColumn();

        return Mission::multiLoad(array('missionIds'=>$missionIds), false);
    }

    public static function getUnacceptedMissions($playerId)
    {
        $player = Yii::app()->objectLoader->load('Player', $playerId);
        $missionIds = Mission::getMissionIdsByLevel($player->level);
        $command = Yii::app()->db->createCommand('SELECT missionId FROM missionRecord WHERE playerId = :playerId AND missionId < 1000 AND status >='.MISSIONRECORD_NEW);
        $acceptedCommonMissionIds = $command->bindParam(':playerId', $playerId)->queryColumn();
        $command = Yii::app()->db->createCommand('SELECT missionId FROM missionRecord WHERE playerId = :playerId AND missionId >= 1000 AND (status >='.MISSIONRECORD_NEW.' AND status < '.MISSIONRECORD_CONFIRMED.')');
        $acceptedRandomMissionIds = $command->bindParam(':playerId', $playerId)->queryColumn();
        $acceptedMissionIds = array_merge($acceptedCommonMissionIds, $acceptedRandomMissionIds);

        return static::getMissionByIds(array_diff($missionIds, $acceptedMissionIds));
    }

    public static function getAcceptableMissionIds($playerId)
    {
        $unacceptedMissions = self::getUnacceptedMissions($playerId);

        if (!empty($unacceptedMissions)) {
            $acceptableMissions = array();
            $acceptableMissionIds = array();
            $command = Yii::app()->db->createCommand('SELECT createTime FROM missionRecord WHERE playerId = :playerId AND missionId >= '.MISSION_SEEDRANDOMID.' ORDER BY createTime DESC');
            $lastTime = $command->bindValue(':playerId', $playerId)->queryScalar(); 
            $preLevel = 0;
            foreach ($unacceptedMissions as $unacceptedMission) {
                if ($unacceptedMission->isAcceptable($playerId)) {
                    if ($unacceptedMission->missionId>=MISSION_SEEDRANDOMID) {
                        if (time()-$lastTime>=MISSION_RANDOM_TIME) {
                            if ($unacceptedMission->missionId==MISSION_SEEDRANDOMID) {
                                $acceptableMissions[MISSION_SEEDRANDOM] = $unacceptedMission->missionId;
                            } else {
                                //前置等级高的随机任务优先
                                if ($unacceptedMission->preLevel>$preLevel) {
                                    $preLevel = $unacceptedMission->preLevel;
                                    $acceptableMissions[MISSION_RANDOM] = array();
                                }
                                $acceptableMissions[MISSION_RANDOM][] = $unacceptedMission->missionId;
                            }
                        }
                    } else {
                        $acceptableMissions[MISSION_COMMON][] = $unacceptedMission->missionId;
                    }
                }
            }

            $unconfirmRecordsCount = MissionModel::getUnconfirmRecordCount($playerId);
            $recordsCountForList = MISSIONRECORD_ACCEPT_MAX - $unconfirmRecordsCount;

            $i = 0;
            if (!empty($acceptableMissions[MISSION_COMMON])) {
                foreach ($acceptableMissions[MISSION_COMMON] as $commonMission) {
                    if ($i<$recordsCountForList) {
                        $acceptableMissionIds[] = $commonMission;
                        $i++;
                        //全局消息
                        $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$playerId);
                        $globalMessage->addMessage($commonMission, $messageType=MESSAGE_TYPE_NEW_MISSION, $params=array());
                    } else {
                        break;
                    }
                }
            }
            if ($i<$recordsCountForList) {
                if (!empty($acceptableMissions[MISSION_SEEDRANDOM])) {
                    $acceptableMissionIds[] = $acceptableMissions[MISSION_SEEDRANDOM];
                    $i++;
                    //全局消息
                    $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$playerId);
                    $globalMessage->addMessage($acceptableMissions[MISSION_SEEDRANDOM], $messageType=MESSAGE_TYPE_NEW_MISSION, $params=array());
                }
                if (!empty($acceptableMissions[MISSION_RANDOM])) {
                    while ($i<$recordsCountForList) {
                        $randomMissionId = $acceptableMissions[MISSION_RANDOM][array_rand($acceptableMissions[MISSION_RANDOM])];
                        $acceptableMissionIds[] = $randomMissionId;
                        $i++;

                        //全局消息
                        $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$playerId);
                        $globalMessage->addMessage($randomMissionId, $messageType=MESSAGE_TYPE_NEW_MISSION, $params=array());
                    }
                }
            }

            return array_unique($acceptableMissionIds);
        } else {
            return array();
        }

    }

    public static function checkMissionStatus($playerId, $missionId)
    {
        $id = MissionRecord::findId($playerId, $missionId);
        if ($id) {
            $missionRecord = Yii::app()->objectLoader->load('missionRecord', $id);
            if ($missionRecord->status>=MISSIONRECORD_COMPLETED) {
                return true;
            }
        }

        return false;
    }

    public static function getMissionByIds($missionIds)
    {
        return Mission::multiLoad(array('missionIds' => $missionIds), false);
    }

    public static function getMissionRecords($playerId)
    {
        $command = Yii::app()->db->createCommand('SELECT id FROM missionRecord WHERE playerId = :playerId AND status<'.MISSIONRECORD_CONFIRMED.' AND status>'.MISSIONRECORD_CANCEL);
        $ids = $command->bindValue(':playerId', $playerId)->queryColumn();

        if (!empty($ids)) {
            return MissionRecord::multiLoad(array('ids'=>$ids));
        } else {
            return array();
        }

    }

    public static function recordsCount($playerId)
    {
        $command = Yii::app()->db->createCommand("SELECT COUNT(*) FROM missionRecord WHERE playerId = :playerId AND status<".MISSIONRECORD_CONFIRMED." AND status>".MISSIONRECORD_CANCEL." GROUP BY playerId");
        $count = $command->bindParam(':playerId', $playerId)->queryScalar();
        return $count ? $count : 0;
    }

    public static function haveNew($playerId)
    {
        $command = Yii::app()->db->createCommand("SELECT COUNT(*) FROM missionRecord WHERE playerId = :playerId AND status=".MISSIONRECORD_NEW." GROUP BY playerId");
        return $command->bindParam(':playerId', $playerId)->queryScalar()?1:0;
    }
}
?>
