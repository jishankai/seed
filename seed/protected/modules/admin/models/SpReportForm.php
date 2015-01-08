<?php

class SpReportForm extends CFormModel {

    public $searchRange = 0;
    public $searchUserId;

    public function rules() {
        return array(
            array('searchRange', 'required'),
            array('searchRange', 'checkRange'),
            array('searchUserId', 'checkUserid'),
        );
    }

    public function init() {
        
    }

    public function checkRange() {
        if (!$this->hasErrors()) {
            if ($this->searchRange != 0 && $this->searchRange != 1) {
                $this->addError('searchRange', 'Not in Range');
            }
        }
    }

    public function checkUserId($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->searchRange == 1) {
                if (isset($this->searchUserId) and preg_match("/^\d+(,\d+)*$/", $this->searchUserId)) {
                    
                } else {
                    $this->addError('searchUserId', 'Incorrect searchUserId');
                }
            }
        }
    }

    public function reportResult() {
        $resultpsp = array();
        $resultssp = array();
        if ($this->searchRange == 0) {
            $type = 0;
            $selectParam = array('sum(purchaseGold) AS pGoldsum', 'count(purchaseGold) AS pGoldcount');
            $whereParam = array('purchaseGold>0');
            $sql = 'SELECT ' . implode(',', $selectParam) . ' FROM userMoney WHERE ' . implode(' AND ', $whereParam);
            $resultpgold = Yii::app()->db->createCommand($sql)->query();
            $selectParam = array('sum(systemGold) AS sGoldsum', 'count(systemGold) AS sGoldcount');
            $whereParam = array('systemGold>0');
            $sql = 'SELECT ' . implode(',', $selectParam) . ' FROM userMoney WHERE ' . implode(' AND ', $whereParam);
            $resultsgold = Yii::app()->db->createCommand($sql)->query();
            $userNum = Yii::app()->db->createCommand("SELECT count(*) as userNum FROM user")->queryScalar();
            return array('pgold' => $resultpgold, 'sgold' => $resultsgold, 'type' => $type, 'num' => $userNum);
        } else {
            $type = 1;
            $Userid = explode(',', $this->searchUserId);
            foreach ($Userid as &$value) {
                $value = 'userId = ' . $value;
            }
            $selectParam = array('userId', 'purchaseGold', 'systemGold', 'updateTime');
            $whereParam = $Userid;
            $sql = 'SELECT ' . implode(',', $selectParam) . ' FROM userMoney WHERE ' . implode(' OR ', $whereParam);
            $resultugold = Yii::app()->db->createCommand($sql)->query();
            return array('ugold' => $resultugold, 'type' => $type);
        }
    }

}