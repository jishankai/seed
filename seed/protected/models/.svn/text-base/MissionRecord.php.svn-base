<?php
/**
 * MissionRecord
 **/
class MissionRecord extends RecordModel
{
    private static $_ids = array();

    protected static $_category;
    protected static $_isAccepted;

    public $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    public static function attributeColumns()
    {
        return array(
            'playerId', 'missionId', 
            'status', 'process', 'processCount',
            'statusTime', 
            'createTime', 'updateTime',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM missionRecord WHERE id = :id");
        $rowData = $command->bindParam(':id', $this->id)->queryRow();
         
        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'missionRecord', $attributes, array('id'=>$this->id));
    } 

    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "SELECT * FROM missionRecord";
		$conditions = array();
		$bindValues = array();
		if( isset($params['ids']) && is_array($params['ids']) ) {
			$conditions[] = 'id IN ('.implode(',', $params['ids']).')';
		}
		
		if( !empty($conditions) ){
		    $sql .= ' WHERE '.implode(' AND ',$conditions);
		}
		
        return self::multiLoadBySql($sql, 'id', array(), $isSimple);
    } 

    public static function create($createInfo)
    {
        $insertArr = array(
            'status' => MISSIONRECORD_NEW,
        );
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['statusTime'] = time();
        $insertArr['createTime'] = time();

        return DbUtil::insert(Yii::app()->db, 'missionRecord', $insertArr, true);
    }

    public static function findId($playerId, $missionId)
    {
        if (empty(self::$_ids[$missionId])) {
            $command = Yii::app()->db->createCommand("SELECT id FROM missionRecord WHERE playerId = :playerId AND missionId = :missionId GROUP BY id DESC");
            $values = array(
                ':playerId' => $playerId,
                ':missionId' => $missionId,
            );
            $result = $command->bindValues($values)->queryScalar();
            self::$_ids[$missionId] = $result ? $result : 0;
        }
        return self::$_ids[$missionId];
    }

    public function viewed()
    {
        if ($this->status==MISSIONRECORD_NEW) {
            $this->status = MISSIONRECORD_UNCOMPLETED;
            $this->statusTime = time();
            $this->saveAttributes(array('status', 'statusTime'));
            //任务更新回调
            GlobalState::set($this->playerId, array('MISSION_NEW'=>MissionModel::haveNew($this->playerId)));
        } else {
            throw new SException(Yii::t('Mission', 'The mission is not new.'));
        }
    }

    public function complete()
    {
        if ($this->status==MISSIONRECORD_UNCOMPLETED OR $this->status==MISSIONRECORD_NEW) {
            $this->status = MISSIONRECORD_COMPLETED;

            $this->statusTime = time();
            $this->saveAttributes(array('status', 'statusTime'));

            if ($this->missionId>=MISSION_SEEDRANDOMID) {
                $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_RANDOM);
                $achieveEvent->onAchieveComplete();
            }
        } else {
            throw new SException(Yii::t('Mission', 'The mission has completed.'));
        }

    }

    public function confirm()
    {
        if ($this->status==MISSIONRECORD_COMPLETED) {
            $command = Yii::app()->db->createCommand(" UPDATE missionRecord SET status=".MISSIONRECORD_CONFIRMED." WHERE id = :id AND status=".MISSIONRECORD_COMPLETED);
            $effectRows = $command->bindValue(':id', $this->id)->execute();
            if ($effectRows > 0) {
                $mission = Yii::app()->objectLoader->load('Mission', $this->missionId);
                $rewards = $mission->getRewards();
                foreach ($rewards as $reward) {
                    $reward->reward($this->playerId);
                }

                $this->status = MISSIONRECORD_CONFIRMED;
                $this->statusTime = time();
                $this->saveAttributes(array('statusTime'));

                if ($this->missionId != 1) {
                    MissionRecord::initNew($this->playerId);
                }
            }

        } else {
            error_log(Yii::t('Mission', 'The mission can not confirmed.'));
        }

    }

    public function cancel()
    {
        if ($this->status != MISSIONRECORD_COMPLETED) {
            $this->status = MISSIONRECORD_CANCEL;
            $this->statusTime = time();
            $this->saveAttributes(array('status', 'statusTime'));
            //任务更新回调
            GlobalState::set($this->playerId, array('MISSION_COUNT'=>MissionModel::recordsCount($this->playerId)));
        } else {
            throw new SException(Yii::t('Mission', 'The mission has not be accepted.'));
        }

    }

    public function getGatherProcess()
    {
        if (isset($this->process) && $this->process!='') {
            $process = unserialize($this->process);
            return $process ? $process : array();
        } else {
            return array();
        }
    }

    public function setGatherProcess($process)
    {
        if (empty($process)) {
            $this->process = NULL;
        } else {
            $this->process = serialize($process);
        }
        $this->saveAttributes(array('process'));
    }

    public function getAccumulateProcess()
    {
        if (isset($this->process) && $this->process!='') {
            return intval($this->process);
        } else {
            return 0;
        }
    }

    public function setAccumulateProcess($process)
    {
        if (empty($process)) {
            $this->process = 0;
        } else {
            $this->process = $process;
        }
        $this->saveAttributes(array('process'));
    }

    public static function initNew($playerId)
    {
        $eventLock = new EventLock() ;     
        $eventLock->getLock('InitializeMission',$playerId) ;
 
        $acceptableMissionIds = MissionModel::getAcceptableMissionIds($playerId);
        foreach ($acceptableMissionIds as $acceptableMissionId) {
            if ($acceptableMissionId == MISSION_SEEDRANDOMID) {
                $seedData = SeedData::getPlayerUnlockData($playerId) ;
                $bodyId = array_rand( $seedData['body'] );
                $faceId = array_rand( $seedData['face'] );
                $budId = array_rand( $seedData['bud'] );
                MissionRecord::create(array('playerId'=>$playerId, 'missionId'=>$acceptableMissionId, 'process'=>$bodyId.','.$faceId.','.$budId));
            } else {
                MissionRecord::create(array('playerId'=>$playerId, 'missionId'=>$acceptableMissionId));
            }
        }
        //购买花园任务检查
        $missionEvent = new MissionEvent($playerId, MISSIONEVENT_GARDENBUY);
        $missionEvent->onMissionComplete();
        //种子收集任务检查
        $missionEvent = new MissionEvent($playerId, MISSIONEVENT_SEEDBUD);
        $missionEvent->onMissionComplete();
        $missionEvent = new MissionEvent($playerId, MISSIONEVENT_SEEDBODY);
        $missionEvent->onMissionComplete();
        $missionEvent = new MissionEvent($playerId, MISSIONEVENT_SEED);
        $missionEvent->onMissionComplete();
        //种子放满花园任务检查
        $missionEvent = new MissionEvent($playerId, MISSIONEVENT_SEEDPLANT);
        $missionEvent->onMissionComplete();
        //任务更新回调
        GlobalState::set($playerId, array('MISSION_COUNT'=>MissionModel::recordsCount($playerId), 'MISSION_NEW'=>MissionModel::haveNew($playerId)?1:0));

        $eventLock->unlock('InitializeMission',$playerId);

    }

    public static function backup()
    {
        $transferCommand = Yii::app()->db->createCommand('INSERT INTO missionHistory (playerId, missionId, status, process, processCount, statusTime)
            SELECT playerId, missionId, status, process, processCount, statusTime FROM missionRecord
            WHERE status IN ('.MISSIONRECORD_CANCEL.', '.MISSIONRECORD_CONFIRMED.') AND missionId >= 1000');
        $transferCommand->execute();

        $removeCommand = Yii::app()->db->createCommand('DELETE FROM missionRecord WHERE status IN ('.MISSIONRECORD_CANCEL.', '.MISSIONRECORD_CONFIRMED.') AND missionId >= 1000');
        $removeCommand->execute();
    }
}
?>
