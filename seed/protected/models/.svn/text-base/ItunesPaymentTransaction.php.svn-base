<?php

class ItunesPaymentTransaction extends RecordModel
{
    public $transaction_id;
    
    function __construct($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }

    public static function attributeColumns()
    {
        return array(
            'transaction_id', 'id', 'sns_id',
            'userId', 'transaction_status', 'product_id', 'price', 
            'quantity', 
            'systemGold', 'record_Time', 'update_Time',
            'purchaseGold',
        );
    }

    public function loadData()
    {
        $command = Yii::app()->db->createCommand("SELECT * FROM ItunesPaymentTransaction WHERE transaction_id = :transaction_id");
        $rowData = $command->bindParam(':transaction_id', $this->transaction_id)->queryRow();
        
        return $rowData;
    }

    public function saveData($attributes=array())
    {
        return DbUtil::update(Yii::app()->db, 'ItunesPaymentTransaction', $attributes, array('transaction_id'=>$this->transaction_id));
    } 

    public static function multiLoad($params=array(), $isSimple=true)
    {
        return ; 
    } 

    public static function create($createInfo)
    {
        return ; 
    }

}
