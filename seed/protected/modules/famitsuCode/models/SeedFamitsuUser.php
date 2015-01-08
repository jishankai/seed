<?php
/**
 * Class SeedFamitsuUser is the model implements IFamitsuUser to stand for the user applying famitsu code.
 * 
 * This class is used by Seed application for its famitsu code campaign. It can get userId from playerId and giveout rewards in 
 * resident world.
 */
class SeedFamitsuUser extends CModel implements IFamitsuUser
{
    private $_playerId;
    private $_userId;

    private $_appliedCodeNum;

    /**
     * @var The rewards for famitsu code. Keys are itemId in itemMeta table, values a number of rewards.
     */
    public $rewards = array(
        31 => 10, 
        1097 => 1,
    );

    public function __construct($userId)
    {
        $this->_userId = $userId;
    }

    public function attributeNames()
    {
        return array('playerId', 'userId', 'rewards');
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function getPlayerId()
    {
        if(!isset($this->_playerId)){
            $this->_playerId =  Player::findIdByUserId($this->userId);
        }
        return $this->_playerId;
    }

    public function getPlayerName()
    {
        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
        return $player->playerName;
    }

    /**
     * Returns the rewards array. Each array element includes item object(indexed by 'item') and its number(indexed by 'num');
     * @return array the rewards array.
     */
    public function getRewards()
    {
        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
        $rewards = array();
        foreach($this->rewards as $itemId=>$num){
            $item = Yii::app()->objectLoader->load('ItemMeta', $itemId);;
            $rewards[] = array('item'=>$item, 'num'=>$num);
        }
        return $rewards;
    }

    public function receiveRewards()
    {
        $item = Yii::app()->objectLoader->load('Item', $this->playerId);
        foreach($this->rewards as $itemId=>$num){
            $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemId);
            $item->addItem($itemMeta, 'famitsuCode', $num);
        }
    }

    public function applyCode($code)
    {
        $SeedTransaction = Yii::app()->db->beginTransaction();
        try{
            $success = $code->apply();
            if(!$success){
                throw new CException(Yii::t('FamitsuCodeModule.Exception', "Failed to apply code"));
            }
            $this->_appliedCodeNum += 1;
            $this->receiveRewards();
            $SeedTransaction->commit();
        }catch(Exception $e){
            $SeedTransaction->rollback();
            throw $e;
        }
    }

    /**
     * Returns the numbers of codes this user has applied
     * @return integer the number of codes this user has applied.
     */
    public function getAppliedCodeNum()
    {
        if(!isset($this->_appliedCodeNum)){
            $command = Yii::app()->db->createCommand("SELECT count(*) FROM famitsuCode WHERE userId = :userId AND deleteFlag = 0");
            $this->_appliedCodeNum = $command->bindValue(':userId', $this->getUserId())->queryScalar();
        }
        return $this->_appliedCodeNum;
    }

    /**
     * Returns the numbers of remain codes this user may apply.
     * @return integer the number of remain codes this user may apply.
     */
    public function getRemainedCodeNum()
    {
        $module = Yii::app()->getModule('famitsuCode');

        return $module->maxCodesPerUser - $this->getAppliedCodeNum();
    }
}
