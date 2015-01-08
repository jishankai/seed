<?php

/**
 * This is the model class for table "adminUser".
 *
 * The followings are the available columns in table 'adminUser':
 * @property string $adminId
 * @property string $username
 * @property string $password
 * @property string $token
 * @property integer $endTime
 * @property integer $deleteFlag
 */
class AdminUser extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AdminUser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'adminUser';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, password', 'required'),
            array('username', 'unique'),
            array('endTime, deleteFlag', 'numerical', 'integerOnly' => true),
            array('username, password, token', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('adminId, username, password, token, endTime, deleteFlag', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'adminId' => 'Admin',
            'username' => 'Username',
            'password' => 'Password',
            'token' => 'Token',
            'endTime' => 'End Time',
            'deleteFlag' => 'Delete Flag',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('adminId', $this->adminId, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('token', $this->token, true);
        $criteria->compare('endTime', $this->endTime);
        $criteria->compare('deleteFlag', $this->deleteFlag);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getUserByUsername($username = '') {
        $criteria = new CDbCriteria();
        $criteria->addCondition(array(
            'deleteFlag = 0',
            'username = :username',
        ));
        $criteria->limit = 1;
        $criteria->params = array(
            ':username' => $username,
        );

        return $this->findAll($criteria);
    }

}