<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '/layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public function filters() {

        return array(

        );
    }

    public function createUrl($route, $params = array(), $ampersand = '&') {
        $params['SID'] = Yii::app()->session->getSessionId();
        if ($route === '')
            $route = $this->getId() . '/' . $this->getAction()->getId();
        else if (strpos($route, '/') === false)
            $route = $this->getId() . '/' . $route;
        if ($route[0] !== '/' && ($module = $this->getModule()) !== null)
            $route = $module->getId() . '/' . $route;
        return Yii::app()->createUrl(trim($route, '/'), $params, $ampersand);
    }

    public function actions() {
        return array(
            'captcha'=>array(
                    'class'=>'CCaptchaAction',
                    'backColor'=>0xFFFFFF,  //背景颜色
                    'minLength'=>4,  //最短为4位
                    'maxLength'=>4,   //是长为4位
                    'transparent'=>true,  //显示为透明
        	),
        );
    }
    
	protected static function dump($var, $title = 'DEBUG DUMP') {
        echo "<fieldset><legend style='font-size:14px;color:#f00'> $title </legend><pre style='font-size:12px; color:#666;'>";
        //var_dump($var);
        print_r($var);
        echo "</pre></fieldset>";
    }


}
