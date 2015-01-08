<?php
class Player extends RecordModel
{
    public $playerId;

    public function __construct($playerId=null)
    {
        $this->playerId = $playerId;

        $this->attachBehavior('PlayerAttributes', new PlayerAttributes);
        $this->attachBehavior('Invite', new Invite);
        $this->attachBehavior('PlayerPointManager', new PlayerPointManager($this->playerId));
        $this->attachBehavior('PlayerLevelUp', new PlayerLevelUp);
        $this->attachBehavior('PlayerStatus', new PlayerStatus($this->playerId));
    }

    public static function attributeColumns()
    {
        return array(
            'userId', 'playerName', 
            'inviteId', 'inviterId',
            'exp', 'level', 'gold',
            'gardenNum', 'defaultGarden', 'favouriteGarden', 'favouriteSeed', 'visitedCount',
            'messageData','settingData','sessionId',
            'createTime', 'updateTime',
        );
    }
   
    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM player WHERE playerId = :playerId");
        $rowData = $command->bindParam(':playerId', $this->playerId)->queryRow();
        
        return $rowData;
    }
    
    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'player', $attributes, array('playerId'=>$this->playerId));
    }

    /**
     * 批量载入Player数据
     *
     * @return array
     * @author Ji.Shankai
     **/
    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "SELECT * FROM player";
		$conditions = array();
		$bindValues = array() ;
		if( isset($params['playerId']) ) {
			$conditions[] = 'playerId=:playerId';
			$bindValues[':playerId'] = $params['playerId'] ;
		}
		
		if( !empty($conditions) ){
		    $sql .= ' WHERE '.implode(' AND ',$conditions);
		}
		
        return self::multiLoadBySql($sql, 'playerId', array(), $isSimple);
    }

    /**
     * 创建Player记录
     *
     * @author Ji.Shankai
     **/
    public static function create($createInfo)
    {
        $insertArr = array();
        foreach (self::attributeColumns() as $key) {
            if (isset($createInfo[$key])) {
                $insertArr[$key] = $createInfo[$key];
            }
        }
        $insertArr['exp'] = PLAYER_EXP;
        $insertArr['level'] = PLAYER_LEVEL;
        $insertArr['gold'] = PLAYER_GOLD;
        $insertArr['gardenNum'] = PLAYER_GARDENNUM;
        $insertArr['createTime'] = time();

        $insertId = DbUtil::insert(Yii::app()->db, 'player', $insertArr, true);

        $player = Yii::app()->objectLoader->load('Player', $insertId);
        $player->initPlayer();

        return $player;
    }

    public function saveAttributes($attributeNames=array())
    {
        if (in_array('exp', $attributeNames)) {
            GlobalState::set($this->playerId, 'PLAYER_EXP', $this->exp);
        } 
        if (in_array('level', $attributeNames)) {
            GlobalState::set($this->playerId, 'PLAYER_LEVEL', $this->level);
        }

        return parent::saveAttributes($attributeNames);
    }

    private static function findIdByName($name)
    {
        $command = Yii::app()->db->createCommand("SELECT playerId FROM player WHERE playerName = :playerName");
        return $command->bindValue(':playerName', $name)->queryScalar();
    }

    public static function loadDataByName($name) {
        $playerId = self::findIdByName($name);

        if ($playerId !== false) {
            return Yii::app()->objectLoader->load('Player', $playerId);
        }else {
            return null;
        }
    }

    public static function findIdByUserId($userId)
    {
        $command = Yii::app()->db->createCommand("SELECT playerId FROM player WHERE userId = :userId");
        $command->bindValue(':userId', $userId);
        $playerId = $command->queryScalar();

        return $playerId;        
    }

    public static function findIdByInviteId($inviteId) {
        $command = Yii::app()->db->createCommand("SELECT playerId FROM player WHERE inviteId = :inviteId");
        $command->bindValue(':inviteId', $inviteId);
        $playerId = $command->queryScalar();

        return $playerId;
    }

    public static function findDefaultGardenByPlayerId($playerId)
    {
        $command = Yii::app()->db->createCommand("SELECT defaultGarden FROM player WHERE playerId = :playerId");
        return $command->bindValue(':playerId', $playerId)->queryScalar();
    }

    public function loadDataByUserId($userId) {
        $playerId = static::findIdByUserId($userId);
        if ($playerId === false) {
            return null;
        }else {
            $player = Yii::app()->objectLoader->load('Player', $playerId);

            return $player;
        }
    }

    private function initPlayer()
    {
        PlayerLogin::create(array('playerId'=>$this->playerId));
        UserMoney::create(array('userId'=>$this->userId));
        FriendInfo::create(array('playerId'=>$this->playerId));
        $this->PlayerPointManager->initPlayerPoints();
        Yii::app()->objectLoader->load('PlayerMoney',$this->playerId)->send(USERMONEY_INIT, 'money for new player');
        $this->defaultGarden = $this->favouriteGarden = GardenModel::initGarden($this->playerId);
        $this->saveAttributes(array('defaultGarden', 'favouriteGarden'));
        Item::initItem($this->playerId);
        MissionRecord::initNew($this->playerId);
        //赠送初始种子
        Yii::app()->objectLoader->load('SeedModel',$this->playerId)->generateFirstSeed(); 
        //赠送道具
        $item = Yii::app()->objectLoader->load('Item', $this->playerId);
        $item->addItem(new ItemMeta(1000), 'init');
        $item->addItem(new ItemMeta(31), 'init');
    }

    public function getUserMoney() {
        return Yii::app()->objectLoader->load('PlayerMoney',$this->playerId)->getMoney();
    }

}
?>
