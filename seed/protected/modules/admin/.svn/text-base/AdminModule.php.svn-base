<?php

class AdminModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));

        Yii::app()->setComponents(array(
            'errorHandler' => array(
                'errorAction' => 'admin/site/error',
            ),
        ));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
            
            Yii::app()->homeUrl = array('/admin/default');
            
            if (!Util::checkIp()) {
                return false;
            }
            
            if($controller->uniqueId != 'admin/site'){
                if(Yii::app()->user->isGuest){
                    $controller->redirect($controller->createUrl('site/login'));
                    return false;
                }
            }
            
			return true;
		}
		else
			return false;
	}
}
