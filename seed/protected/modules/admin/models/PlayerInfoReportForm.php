<?php

class PlayerInfoReportForm extends CFormModel {

    public $searchRange = 0;
    public $searchWorldId;
    public $resultType = 0;

    public function rules() {
        return array(
            array('searchRange, resultType', 'required'),
            array('searchRange', 'checkRange'),
            array('searchWorldId', 'checkWorldId'),
            array('resultType', 'checkType'),
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

    public function checkType() {
        if (!$this->hasErrors()) {
            if ($this->resultType != 0 && $this->resultType != 1) {
                $this->addError('resultType', 'Incorrect Type');
            }
        }
    }

    public function checkWorldId($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->searchRange == 1) {
                if (!preg_match("/^\d+$/", $this->$attribute)) {
                    $this->addError($attribute, $attribute . ': should be integer!');
                }
            }
        }
    }

    public function reportResult() {
        $result = Yii::app()->db->createCommand("SELECT count(*) AS num,level FROM player GROUP BY level ORDER BY level ASC")->queryAll();
        $userNum = Yii::app()->db->createCommand("SELECT count(*) as userNum FROM player")->queryScalar();

        return array('result' => $result, 'userNum' => $userNum);
    }

}