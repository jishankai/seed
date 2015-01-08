<?php

class PushController extends Controller {
	public function actionIndex() {
		$model = new PushForm;
		$model->init();
		if (isset($_POST['PushForm'])) {
			$model->attributes = $_POST['PushForm'];
            if ($model->validate()) {
            	$model->addPush();
            }
        }
		$this->render('index', array('model' => $model));
	} 
}