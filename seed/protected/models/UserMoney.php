<?php
/**
 * UserMoney
 **/
class UserMoney extends RecordModel {
    public $userId ;
    protected $userGold ;

    public function __construct($userId){
        $this->userId = $userId ;
        $this->init() ;
    }

    public static function attributeColumns()
    {
        return array(
            'userId','purchaseGold', 'systemGold', 'createTime', 'updateTime',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM userMoney WHERE userId = :userId");
        $rowData = $command->bindParam(':userId', $this->userId)->queryRow();
        if (empty($rowData)) {
            return self::create( array('userId'=>$this->userId) );
        }
        return $rowData;
    }

    public function saveData($attributes=array()) { } 

    public static function multiLoad($params=array(), $isSimple=true) {} 

    public static function create($createInfo)
    {
        $cmd = Yii::app()->db->createCommand("select * from userMoney where userId='$createInfo[userId]'");
        $result = $cmd->queryRow();
        if( empty($result) ) {
            $result = array();
            foreach (self::attributeColumns() as $key) {
                if (isset($createInfo[$key])) {
                    $result[$key] = $createInfo[$key];
                }
            }
            $result['purchaseGold'] = USERMONEY_PURCHASEGOLD;
            $result['systemGold'] = USERMONEY_SYSTEMGOLD;
            $result['createTime'] = time();

            DbUtil::insert(Yii::app()->db, 'userMoney', $result, true);
        }
        return $result ;
    }

    /**
     * Init player purchaseGold.
     */
    public function init(){
        $this->reset();
        $this->userGold = $this->purchaseGold+$this->systemGold ;
    }

    /**
     * Send systemGold to player 
     * adminitrators or system send systemGold use this method
     */
    public function send($value,$comment,$goodsId=0){
        if( empty($value)||$value<0 ) {
            throw  new CException('add value must greater than zero');
        }

        $sql = "update userMoney set systemGold=systemGold+$value where userId='{$this->userId}'";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd -> execute();

        $this->saveLog($value,$comment.";\nSQL:".$sql,$goodsId);
        $this->userGold += $value ;
    }

    /**
     * Add player Gold.
     * user payment use this method.
     */
    public function add($value,$comment,$goodsId=0){
        if( empty($value)||$value<0 ) {
            throw  new CException('add value must greater than zero');
        }

        $sql = "update userMoney set purchaseGold=purchaseGold+$value where userId='{$this->userId}'";
        $cmd = Yii::app()->db->createCommand($sql);
        $effectCount = $cmd -> execute();
        if ($effectCount == 0) {
            throw new SException(Yii::t('View','system error'));
        }

        $this->saveLog($value,$comment.";\nSQL:".$sql,$goodsId);
        $this->userGold += $value ;
    }

    public function reduce($value,$comment,$goodsId=0){
        $this->checkEnough($value);

        $sql = "update userMoney set systemGold=if(purchaseGold<$value,systemGold+purchaseGold-$value,systemGold),purchaseGold=if(purchaseGold>=$value,purchaseGold-$value,0) where userId='{$this->userId}' and systemGold+purchaseGold>=$value";
        $cmd = Yii::app()->db->createCommand($sql);
        $effectCount = $cmd -> execute();
        if ($effectCount == 0) {
            throw new SException('money is not enough', EXCEPTION_TYPE_MONEY_NOT_ENOUGH);
        }

        $this->saveLog($value*(-1),$comment.";\nSQL:".$sql,$goodsId);
        $this->userGold += $value*(-1) ;
    }

    public function checkEnough($value){
        $this->init();
        if( $this->userGold < $value ) {
            throw new SException('money is not enough', EXCEPTION_TYPE_MONEY_NOT_ENOUGH);
        }
    }


    public function getMoney(){
        $this->init();
        return $this->userGold ;
    }

    public function saveLog($value,$comment,$goodsId){
        $dataArray = array(
            'userId'        => $this->userId ,
            'type'          => 1 ,
            'linkId'        => $goodsId ,
            'gold'          => $value ,
            'currentGold'   => $this->userGold ,
            'afterGold'     => $this->userGold + $value ,
            'comment'       => $comment ,
            'createTime'    => time() ,
        );
        DbUtil::insert(Yii::app()->db, 'userMoneyLog', $dataArray) ;
    }
}

