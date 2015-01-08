<?php

class SiteController extends Controller
{
    public function actions()
    {
        return array(
            'check' => array(
                'class' => 'CWebServiceAction',
            ),
        );
    }
    
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
                $session = Yii::app()->session;
                $session['uid'] = $model->username;
                $session['token'] = $model->token;
                $session['sign'] = $model->sign;
				$this->redirect($this->createUrl('default/index'));
            }
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

    /**
     * The soap method to return login or not login.
     * @param string $username the username.
     * @param string $token the token.
     * @param string $sign the sign.
     * @return boolean login or not login
     * @soap
     */
    public function checkLogin($username, $token, $sign)
    {
        if (isset($username) && isset($token) && isset($sign) && Util::makeSign($username, $token) == $sign) {
            $users = new AdminUser();
            $user = $users->find('username = :username', array(':username' => $username));
            if (isset($user) && $user['token'] == $token && $user['endTime'] > time()) {
                $user['token'] = '';
                $user['endTime'] = 0;
                $user->save();
                return true;
            }
        }
        return false;
    }

    public function actionError(){
	    if($error=Yii::app()->errorHandler->error){
            header("HTTP/1.0 200 ");
            if($error['type'] != 'SException'){
                error_log($error['message']);
            }
	    	if(Yii::app()->request->isAjaxRequest)
                $this->error($error['message'], $error['errorCode']);
	    	else
	        	$this->render('error', $error);
	    }
    }
}
