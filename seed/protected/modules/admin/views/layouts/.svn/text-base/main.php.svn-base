<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/form.css" />
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js?v=<?php echo time(); ?>"></script>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>

        <div class="container" id="page" style="max-height: 10000px;" <?php $background = ModuleUtil::loadconfig('admin', 'background');
if ($background['SHOW_BACKGROUND_COLOR'] == 1) {
   ?> style="background-color:<?php echo $background['BACKGROUND_COLOR'] ?>" <?php } ?>>

            <div id="header">
                <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
            </div><!-- header -->

            <div id="mainMbMenu">
                <?php
                $this->widget('application.extensions.mbmenu.MbMenu', array(
                    'items' => array(
                        array('label' => '首页', 'url' => $this->createUrl('default/index')),
                        array('label' => '报告', 'url' => array('report/activeUser')),
                        array('label' => '公告管理', 'url' => array('notice/index')),
                        array('label' => '推送编写', 'url' => array('push/index')),
                        array('label' => '修改密码', 'url' => array('default/changePassword')),
                        array('label' => '环境信息', 'url' => array('default/phpInfo')),
                        array('label' => '数据导入', 'url' => array('import/index'), 'htmlOptions' => array('class' => 'dir'), 'items' => array(
                                array('label' => '任务导入', 'url' => array('import/mission')),
                                array('label' => '成就导入', 'url' => array('import/achievement')),
                        )),
                        array('label' => '用户管理', 'url' => '', 'htmlOptions' => array('class' => 'dir'), 'items' => array(
                                array('label' => '修改密码', 'url' => array('default/changePassword')),
                                array('label' => '新建用户', 'url' => array('adminUser/create')),
                        )),
                        array('label' => '退出 (' . Yii::app()->user->name . ')', 'url' => array('default/logout'), 'visible' => !Yii::app()->user->isGuest),
                    ),
                ));
                ?>
            </div><!-- mainmenu -->

            <?php
//            $this->widget('zii.widgets.CBreadcrumbs', array(
//                'links' => $this->breadcrumbs,
//            ));
            ?><!-- breadcrumbs -->
<?php echo $content; ?>

            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
                All Rights Reserved.<br/>
<?php echo Yii::powered(); ?>
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>
