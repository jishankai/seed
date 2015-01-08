<?php
/**
 * Mission
 **/
class Mission extends RecordModel
{
    public $missionId;
    
    function __construct($missionId)
    {
        $this->missionId = $missionId;
    }

    public static function attributeColumns()
    {
        return array(
            'title', 'description', 'endCondition',
            'count', 'event', 'checkClass', 'expectedParams', 
            'preLevel', 'preMissionId', 
            'endImage', 'endCount', 'endPre', 'endNext',
            'rewardSeed', 'rewardItem', 'rewardGold', 'rewardUserMoney', 'rewardExp',
            'createTime', 'updateTime',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM mission WHERE missionId = :missionId");
        $rowData = $command->bindParam(':missionId', $this->missionId)->queryRow();
        
        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'mission', $attributes, array('missionId'=>$this->missionId));
    } 

    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "SELECT * FROM mission";
		$conditions = array();
		$bindValues = array() ;
        
		if( isset($params['missionIds']) && is_array($params['missionIds']) ) {
			$conditions[] = 'missionId IN ('.implode(',', $params['missionIds']).')';
		}
		
		if( !empty($conditions) ){
		    $sql .= ' WHERE '.implode(' AND ',$conditions);
        }
		
        return self::multiLoadBySql($sql, 'missionId', $bindValues, $isSimple);
    } 

    public static function create($createInfo)
    {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['createTime'] = time();

        return DbUtil::insert(Yii::app()->db, 'mission', $insertArr, true);
    }

    public function checkComplete($event)
    {
        $class = $this->checkClass;
        if (!class_exists($class)) {
            throw new CException(Yii::t('Mission', 'MissionChecker class {class} not exists', array('{class}'=>$class)));
        }
        if(isset($this->expectedParams) && $this->expectedParams != '') {
            $expectedParams = $this->expectedParams;
            $checker = new $class($this->missionId, $expectedParams);
        } else {
            $checker = new $class($this->missionId);
        }

        return $checker->checkComplete($event);
    }

    public function getRewards()
    {
        $rewards = array();
        $title = $this->title;

        if(isset($this->rewardExp) && $this->rewardExp != 0){
            $rewards['exp'] = new ExpReward($this->rewardExp);
        }

        if(isset($this->rewardSeed) && $this->rewardSeed != 0){
            $seedArray = explode(';', $this->rewardSeed); 
            foreach ($seedArray as $seed) {
                $seedDetail = explode('_', $seed);
                $rewards[$seedDetail[0]] = new SeedReward($seedDetail[0], $seedDetail[1], 'MISSION', $title);
            }
        }

        if(isset($this->rewardItem) && $this->rewardItem != ''){
            $itemArray = explode(';', $this->rewardItem); 
            foreach ($itemArray as $item) {
                $itemDetail = explode('_', $item);
                $rewards[$itemDetail[0]] = new ItemReward($itemDetail[0], $itemDetail[1], 'MISSION', $title);
            }
        }

        if(isset($this->rewardGold) && $this->rewardGold != 0){
            $rewards['gold'] = new GoldReward($this->rewardGold, 'MISSION', $title);
        }

        if(isset($this->rewardUserMoney) && $this->rewardUserMoney != 0){
            $rewards['userMoney'] = new UserMoneyReward($this->rewardUserMoney, 'MISSION', $title);
        } 

        return $rewards;
    }

    public static function getMissionIds()
    {
        $command = Yii::app()->db->createCommand('SELECT missionId FROM mission');
        return $command->queryColumn();
    }

    public static function getMissionIdsByLevel($level)
    {
        $command = Yii::app()->db->createCommand('SELECT missionId FROM mission WHERE preLevel <= :playerLevel');
        return $command->bindParam(':playerLevel', $level)->queryColumn();
    }

    public static function findMissionId($missionId)
    {
        $command = Yii::app()->db->createCommand('SELECT missionId FROM mission WHERE missionId = :missionId');
        return $command->bindParam(':missionId', $missionId)->queryScalar();
    }

    public function isAcceptable($playerId)
    {
        if ($this->missionId>MISSION_SEEDRANDOMID) {
            $command = Yii::app()->db->createCommand('SELECT COUNT(*) FROM missionRecord WHERE playerId = :playerId AND createTime >= :today AND missionId = :missionId GROUP BY playerId');
            $bindValue = array(
                ':playerId' => $playerId,
                ':missionId' => $this->missionId,
                ':today' => strtotime(date('Y-m-d 04:00:00')), 
            );
            $missionCount = $command->bindValues($bindValue)->queryScalar();
            if ($this->count <= $missionCount) {
                return false;
            }
        }
        if (!empty($this->preMissionId)) {
            $preMissionIds = explode(',', $this->preMissionId);
            foreach ($preMissionIds as $preMissionId) {
                $id = MissionRecord::findId($playerId, $preMissionId);
                if ($id) {
                    $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
                    if ($missionRecord->status<=MISSIONRECORD_COMPLETED) {
                        return false;
                    }
                } else {
                    return false;
                }
                
            }
        }
        if (!empty($this->preLevel)) {
            $player = Yii::app()->objectLoader->load('Player', $playerId);
            if ($this->preLevel>$player->level) {
                return false;
            } else if ($this->missionId>MISSION_SEEDRANDOMID) {
                if ($this->preLevel+5<$player->level and $player->level<=60) {
                    return false; 
                }
            }
        }

        return true;
    }
}
?>
