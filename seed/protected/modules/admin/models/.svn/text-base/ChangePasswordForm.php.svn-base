<?php

class ChangePasswordForm extends CFormModel {

    public $oldPassword;
    public $newPassword;
    public $newPasswordAgain;
    private $_identity;

    public function rules() {
        return array(
            array('oldPassword, newPassword, newPasswordAgain', 'required'),
            array('oldPassword', 'authenticate'),
            array('newPasswordAgain', 'equal'),
        );
    }

    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity(Yii::app()->user->name, $this->oldPassword);
            if (!$this->_identity->authenticate())
                $this->addError('oldPassword', 'Incorrect old password.');
        }
    }

    public function equal($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->newPassword != $this->newPasswordAgain)
                $this->addError('newPasswordAgain', 'The new password again is incorrect.');
        }
    }

    public function change() {
        $user = new AdminUser();
        $user = $user->find('username = :username', array(':username' => Yii::app()->user->name));
        $user->password = md5($this->newPassword);
        $user->save();
        return true;
    }

}