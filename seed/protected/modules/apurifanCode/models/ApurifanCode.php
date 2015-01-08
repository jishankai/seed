<?php
/**
 * FamitusCode class file.
 *
 * @author Qi Changhai <qi.changhai@adways.net>
 * @copyright Copyright &copy; 2004-2011 Adways Ltd.
 * @license
 */

/**
 * ApurifanCode is the class represents a code published by famitsu and used in your application.
 * 
 */
class ApurifanCode extends CModel
{
    public $code;
    public $userId;
    public $deleteFlag;

    public function attributeNames()
    {
        return array('code', 'userId', 'deleteFlag');
    }

    /**
     * Rules to validate data.
     */
    public function rules()
    {
        return array(
            array('code', 'hasBegun'),
            array('code', 'hasNotEnd'),
            array('code', 'required', 'message'=>Yii::t('ApurifanCodeModule.View', 'Please input code.')),
            array('code', 'length', 'min'=>16, 'max'=>19, 'message'=>Yii::t('ApurifanCodeModule.View', 'Code should be 16 digits.'), 'tooLong'=>Yii::t('ApurifanCodeModule.View', 'Code should be 16 digits.'), 'tooShort'=>Yii::t('ApurifanCodeModule.View', 'Code should be 16 digits.')),
            array('code', 'match', 'pattern'=>'/^([0-9\-])+$/', 'message'=>Yii::t('ApurifanCodeModule.View', 'Code is incorrect.')),
            array('userId', 'maxCodes', 'skipOnError'=>true),
            array('code', 'exist', 'allowEmpty'=>false),
            array('userId', 'required'),
            array('userId', 'numerical'),
        );
    }

    /**
     * Validate if the campaign has begun.
     */
    public function hasBegun($attribute, $params)
    {
        if(!Yii::app()->getModule('apurifanCode')->hasBegun()){
            $this->addError($attribute, Yii::t('ApurifanCodeModule.View', 'The campaign has not begun.'));
        }
    }

    /**
     * Validate if the campaign has not yet ended.
     */
    public function hasNotEnd($attribute, $params)
    {
        if(Yii::app()->getModule('apurifanCode')->hasEnded()){
            $this->addError($attribute, Yii::t('ApurifanCodeModule.View', 'The campaign has ended.'));
        }
    }

    /**
     * Validate the eixistance of code.
     */
    public function exist($attribute, $params)
    {
        $code = preg_replace('/-/', '', $this->code);
        $command = Yii::app()->db->createCommand("SELECT * FROM apurifanCode WHERE code = :code AND deleteFlag = 0");
        $data = $command->bindValue(':code', $code)->queryRow();
        if ($data['id'] === null) {
            $this->addError($attribute, Yii::t('ApurifanCodeModule.View', 'Code is incorrect.'));
        } else if ($data['userId'] !== null) {
            $this->addError($attribute, Yii::t('ApurifanCodeModule.View', 'This code has been applied.'));
        }
    }


    /**
     * Validate whether the user has applied too many codes.
     */ 
    public function maxCodes($attribute, $params)
    {
        $module = Yii::app()->getModule('apurifanCode');
        $userClass = $module->userClass;
        if(class_exists($userClass)){
            $user = new $userClass($this->userId);
            if($user->getRemainedCodeNum() <= 0){
                $message = Yii::t('ApurifanCodeModule.View', 'Every user can not apply more than {maxCodes} codes.', array('{maxCodes}'=>$module->maxCodesPerUser));
                $this->addError($attribute, $message);
            }
        }else{
            throw new CException(Yii::t('ApurifanCodeModule.Exception', 'Required class {class} not defined', array('{class}'=>$userClass)));
        }
    }

    /**
     * User apply code.
     * Note: 
     * validation is not performed in this method.
     * @return boolean whether the apply is success.
     */
    public function apply()
    {
        if(!isset($this->code)){
            throw new CException(Yii::t('ApurifanCodeModule.Exception', 'Code is not applied.'));
        }
        if(!isset($this->userId)){
            throw new CException(Yii::t('ApurifanCodeModule.Exception', 'UserId is not applied.'));
        }

        $code = preg_replace('/-/', '', $this->code);
        $command = Yii::app()->db->createCommand("UPDATE apurifanCode SET userId = :userId WHERE code = :code AND deleteFlag = 0");
        $values = array(
            ':userId' => $this->userId,
            ':code' => $code,
        );
        $rows = $command->execute($values);
        if($rows > 0){
            if(YII_DEBUG){
                Yii::trace("Code $this->code is applied by user $this->userId.", 'apurifanCode');
            }
            return true;
        }else{
            return false;
        }
    }
}
