<?php
/**
 * Achievement
 *
 * @author Ji.Shankai
 * @version 1.0
 * @copyright Ji.Shankai, 2012-03-01
 * @package Seed
 **/

class Achievement extends RecordModel
{
    public $achievementId;

    function __construct($achievementId)
    {
        $this->achievementId = $achievementId;
    }

    public static function attributeColumns()
    {
        return array(
            'achievementId', 'title', 'description', 'category', 'class',
            'event', 'checkClass', 'expectedParams', 'paramsCount', 
            'rewardItem', 'rewardCup', 'rewardExp', 'rewardGold', 'rewardUserMoney',
            'createTime', 'updateTime',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM achievement WHERE achievementId = :achievementId");
        $rowData = $command->bindParam(':achievementId', $this->achievementId)->queryRow();
       
        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'achievement', $attributes, array('achievementId'=>$this->achievementId));
    } 

    public static function multiLoad($params=array(), $isSimple=true)
    {
        $sql = "SELECT * FROM achievement";
        $conditions = array();
        $bindValues = array() ;

        if( isset($params['achievementIds']) && is_array($params['achievementIds']) ) {
            $conditions[] = 'achievementId IN ('.implode(',', $params['achievementIds']).')';
        }

        if( !empty($conditions) ){
            $sql .= ' WHERE '.implode(' AND ',$conditions);
        }

        return self::multiLoadBySql($sql, 'achievementId', $bindValues, $isSimple);
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

        return DbUtil::insert(Yii::app()->db, 'achievement', $insertArr, true);
    }

    public function checkComplete($event)
    {
        $class = $this->checkClass;
        if (!class_exists($class)) {
            throw new CException(Yii::t('Achievement', 'AchievementChecker class {class} not exists', array('{class}'=>$class)));
        }

        if(isset($this->expectedParams) && $this->expectedParams != ''){
            $expectedParams = $this->expectedParams;
            $checker = new $class($this->achievementId, $expectedParams);
        }else{
            $checker = new $class($this->achievementId);
        }

        return $checker->checkComplete($event);
    }

    public function getRewards()
    {
        $rewards = array();
        $i = 0;
        $title = $this->title;

        if(isset($this->rewardItem) && $this->rewardItem != ''){
            $itemArray = explode(';', $this->rewardItem); 
            foreach ($itemArray as $item) {
                $itemDetail = explode('_', $item);
                $rewards[$itemDetail[0]] = new ItemReward($itemDetail[0], $itemDetail[1], 'ACHIEVEMENT', $title);
            }
        }

        if(isset($this->rewardCup) && $this->rewardCup != ''){
            $cupArray = explode(';', $this->rewardCup); 
            foreach ($cupArray as $cup) {
                $cupDetail = explode('_', $cup);
                $rewards[$cupDetail[0]] = new CupReward($cupDetail[0], $cupDetail[1]);
            }
        }

        if(isset($this->rewardExp) && $this->rewardExp != 0){
            $rewards['exp'] = new ExpReward($this->rewardExp);
        } 
        if(isset($this->rewardGold) && $this->rewardGold != 0){
            $rewards['gold'] = new GoldReward($this->rewardGold, 'ACHIEVEMENT', $title);
        } 
        if(isset($this->rewardUserMoney) && $this->rewardUserMoney != 0){
            $rewards['userMoney'] = new UserMoneyReward($this->rewardUserMoney, 'ACHIEVEMENT', $title);
        } 

        return $rewards;
    }

    public static function getCategoryById($achievementId)
    {
        $command = Yii::app()->db->createCommand('SELECT category FROM achievement WHERE achievementId = :achievementId');
        return $command->bindValue(':achievementId', $achievementId)->queryScalar();
    }

}
?>
