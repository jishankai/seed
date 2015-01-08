<?php

class PushForm extends CFormModel {
	
	public $content;
	public $searchUserRange = 0;
    public $searchUserId;
    public $sendDate;
    public $state = 1;
    public $verifyCode;
    
	public function rules() {
        return array(
            array('searchUserRange, content, sendDate', 'required'),
            array('searchUserRange', 'checkUserRange'),
            array('searchUserId', 'checkUserId'),
            array('sendDate', 'checkSendDate'),
            array('verifyCode', 'captcha'),
    	);
    }

    public function init() {
        $this->sendDate = self::makeDayStr();
    }
    
	public function checkUserRange() {          //checkUserRange
        if (!$this->hasErrors()) {
            if ($this->searchUserRange != 0 && $this->searchUserRange != 1) {
                $this->addError('searchUserRange', 'Not in UserRange');
            }
        }
    }
    
	public function checkUserId($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->searchUserRange == 1) {
                if (isset($this->searchUserId) && (preg_match("/^\d+$/", $this->searchUserId) || preg_match("/^\d+(,\d+)*$/", $this->searchUserId) || preg_match("/^\d+-\d+$/", $this->searchUserId))) {
                	if (preg_match("/^\d+-\d+$/", $this->searchUserId)) {
                		$playerId=explode("-",$this->searchUserId);
                		if ($playerId[0]>$playerId[1]) {
                			$this->addError('searchUserId', '第一个数字不能大于第二个');
                		}
                	}
                } else {
                    $this->addError('searchUserId', 'Incorrect searchUserId');
                }
            }
        }
    }
    
    public function checkSendDate () {
    	if (!$this->hasErrors()) {
            if (!strtotime ($this->sendDate)) {
                $this->addError('endDate', 'The format of endDate is error');
            }
        }
    }
    
    public function addPush() {
    	if (!$this->hasErrors()) {
    		if ($this->searchUserRange == 0) {
    			$sql = 'SELECT playerId FROM player';
    			$playerIds=Yii::app()->db->createCommand($sql)->queryAll();
    			
    		} else if (preg_match("/^\d+$/", $this->searchUserId)) {
    			$playerIds=array();
    			$playerIds[]=$this->searchUserId;
    		} else if (preg_match("/^\d+(,\d+)*$/", $this->searchUserId)) {
    			$playerIds=explode(",",$this->searchUserId);
    		} else if (preg_match("/^\d+-\d+$/", $this->searchUserId)) {
    			$playerId=explode("-",$this->searchUserId);
    			$playerIds=array();
    			for ($i=$playerId[0];$i<=$playerId[1];$i++) {
    				$playerIds[]=$i;
    			}
    		}
    		$attributeColumns = array('playerId', 'content', 'state', 'sendTime', 'createTime');
    		$count=0;
    		$insertDatas=array();
    		foreach ($playerIds as $Id) {
    			if ($count==5000) {
    				DBUtil::batchInsert(Yii::app()->db, 'pushQueue', $attributeColumns, $insertDatas);
    				$count=0;
    				$insertDatas=array();
    			}
    			$insertData=array();
    			$insertData[]=$Id;
    			$insertData[]=$this->content;
    			$insertData[]=$this->state;
    			$insertData[]=strtotime ($this->sendDate);
    			$insertData[]=time();
    			$insertDatas[] = $insertData;
    			++$count;
    		}
    		DBUtil::batchInsert(Yii::app()->db, 'pushQueue', $attributeColumns, $insertDatas);
    	}
    }
    
	public static function makeDayStr($timestamp = null) {
        if (!isset($timestamp)) {
            $timestamp = time();
        }
        return date('Y/m/d H:i:s', $timestamp);
    }
    
    public static function makeTimeStr($timestamp = null) {
        if (!isset($timestamp)) {
            $timestamp = time();
        }
        return date('H:i:s', $timestamp);
    }
}