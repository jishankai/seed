<?php
/**
 * AchievementRecord
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-01
 * @package Seed
 **/

class AchievementRecord extends RecordModel
{
    private static $_ids = array();
    public $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    public static function attributeColumns()
    {
        return array(
            'playerId', 'achievementId', 'status', 
            'process', 'processCount', 'statusTime',
            'createTime', 'updateTime',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM achievementRecord WHERE id = :id");
        $rowData = $command->bindParam(':id', $this->id)->queryRow();
        
        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'achievementRecord', $attributes, array('id'=>$this->id));
    } 

    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "SELECT * FROM achievementRecord";
		$conditions = array();
		$bindValues = array();
		if( isset($params['ids']) && is_array($params['ids']) ) {
			$conditions[] = 'id IN ('.implode(',', $params['ids']).')';
		}
		
		if( !empty($conditions) ){
		    $sql .= ' WHERE '.implode(' AND ',$conditions);
        }
		
        return self::multiLoadBySql($sql, 'id', $bindValues, $isSimple);
    } 

    public static function create($createInfo)
    {
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['status'] = ACHIEVEMENTRECORD_UNCOMPLETED;
        $insertArr['createTime'] = time();

        return DbUtil::insert(Yii::app()->db, 'achievementRecord', $insertArr, true);
    }

    public static function findId($playerId, $achievementId)
    {
        if (empty(self::$_ids) or in_array($achievementId, array(20,21,22))) {
            $command = Yii::app()->db->createCommand("SELECT id, achievementId FROM achievementRecord WHERE playerId = :playerId");
            $rows = $command->bindValue(':playerId', $playerId)->queryAll();
            foreach ($rows as $row) {
                self::$_ids[$row['achievementId']] = $row['id'];
            }
        }
        if (empty(self::$_ids[$achievementId])) {
            $data = array(
                'playerId' => $playerId,
                'achievementId' => $achievementId,
            );
            $id = AchievementRecord::create($data);
            self::$_ids[$achievementId] = $id;
        }

        return self::$_ids[$achievementId];
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

    public function complete()
    {
        if ($this->status==ACHIEVEMENTRECORD_UNCOMPLETED) {
            $this->status = ACHIEVEMENTRECORD_COMPLETED;
            $this->statusTime = time();
            $this->saveAttributes(array('status', 'statusTime'));

            //领取奖励
            $achievement = Yii::app()->objectLoader->load('Achievement', $this->achievementId);
            $rewards = $achievement->getRewards();
            foreach ($rewards as $reward) {
                $reward->reward($this->playerId);
            }

            //全局消息
            $globalMessage = Yii::app()->objectLoader->load('GlobalMessage',$this->playerId);
            $globalMessage->addMessage($this->achievementId, MESSAGE_TYPE_ACHIEVE_COMPLETE);

            //成就检查
            $achieveEvent = new AchievementEvent($this->playerId, ACHIEVEEVENT_COUNT);
            $achieveEvent->onAchieveComplete();
        } else {
            throw new SException(Yii::t('Achievement', 'The achievement has completed.'));
        }

    }

    public static function getCompletedCount($playerId)
    {
        $command = Yii::app()->db->createCommand('SELECT COUNT(*) FROM achievementRecord WHERE playerId = :playerId AND status = '.ACHIEVEMENTRECORD_COMPLETED.' GROUP BY playerId');
        $completedCount = $command->bindValue(':playerId', $playerId)->queryScalar();

        return $completedCount ? $completedCount : 0;
    }

    public static function getTotalCount($playerId)
    {
        $command = Yii::app()->db->createCommand('SELECT COUNT(*) FROM achievement');
        return $command->bindValue(':playerId', $playerId)->queryScalar();
    }

}
?>
