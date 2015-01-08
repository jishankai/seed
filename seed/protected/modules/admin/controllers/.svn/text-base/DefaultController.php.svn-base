<?php

class DefaultController extends Controller {

    public function actionIndex() {
        $session = Yii::app()->session;
        if (!(isset($session['uid']) && !empty($session['uid']) && isset($session['token']) && !empty($session['token']) && isset($session['sign']) && !empty($session['sign']))) {
            throw new CException('No token exists.');
        }

        $this->render('index', array('uid' => $session['uid'], 'token' => $session['token'], 'sign' => $session['sign']));
    }

    public function actionLogout() {
        $session = Yii::app()->session;
        $session->clear();

        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionPhpInfo() {
        phpinfo();
    }

    public function actionChangePassword() {
        $model = new ChangePasswordForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['ChangePasswordForm'])) {
            $model->attributes = $_POST['ChangePasswordForm'];
            print_r($_POST['ChangePasswordForm']);
            if ($model->validate() && $model->change()) {
                $this->redirect($this->createUrl('default/index'));
            }
        }
        $this->render('changePassword', array('model' => $model));
    }

}
