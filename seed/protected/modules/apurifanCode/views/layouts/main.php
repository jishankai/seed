<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="Cache-Control" content="no-cache,must-revalidate" />
        <meta http-equiv="expires" content="0" />
        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css?v=<?php echo time(); ?>" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css?v=<?php echo time(); ?>" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/basic.css?v=<?php echo time(); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css?v=<?php echo time(); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css?v=<?php echo time(); ?>" />

        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.5.2.min.js?v=<?php echo time(); ?>"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js?v=<?php echo time(); ?>"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/flipsnap.js?v=<?php echo time(); ?>"></script>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div id="loadingMessage" style="height:320px; line-height:320px; text-align:center;">页面载入中....</div>
        <div class="container" id="page">

            <div id="header">
                <div id="logo"> 
                    <?php if( !empty($this->playerId) ) {
                        $player = Yii::app()->objectLoader->load('Player', $this->playerId);
                        echo '<div class="fl w40">ID:'.$this->playerId.'</div>';
                        $this->widget('application.components.widget.PlayerMessage');?>
                        <img src="<?php echo $this->createUrl('/player/name');?>" />
                        <input type="button" value="关闭" class="w50 h30 lh30 fr" id="systemCloseButton">
                        <input type="button" value="+系统菜单" class="w90 h30 lh30 fr" id="systemMenuButton">
                    <?php } ?>
                </div> 
            </div><!-- header -->

            <div id="mainmenu">
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'items' => array(
                        //array('label' => '主页Demo', 'url' => array('/site/modelDoc')),
                        array('label' => '种子信息', 'url' => array('/seed/view')),
                        array('label' => '种子效果', 'url' => array('/seed/show')),
                        array('label' => '登录/注册', 'url' => array('/login/index')),
                        array('label' => '玩家信息', 'url' => array('/player/getIndex')),
                        array('label' => '花园列表', 'url' => array('/garden/gardenList')),
                        array('label' => '好友列表', 'url' => array('/friend/index')),
                        array('label' => '地图探索', 'url' => array('/map/index')),
                        array('label' => '仓库信息', 'url' => array('/items/decoShow')),
                        array('label' => '道具信息', 'url' => array('/items/itemShow')),
                        array('label' => '商店', 'url' => array('/shop/index')),
                        array('label' => '图鉴', 'url' => array('/catalog')),
                        array('label' => '邮件信息', 'url' => array('/mail/mailShow')),
                        array('label' => '添加邮件', 'url' => array('/mail/displayCreate')),
                        array('label' => '任务列表', 'url' => array('/mission/index')),
                        array('label' => '成就列表', 'url' => array('/achieve/index')),
                        array('label' => '帐号绑定', 'url' => array('/garden/ShowSns')),
                    ),
                ));
                ?>
                <div style="clear:both;"></div>
            </div><!-- mainmenu -->


            <?php echo $content; ?>

        </div><!-- page -->
<script type="text/javascript">
<!--
$(window).load( function(){
    $('#loadingMessage').hide();
    $('#page').show();
    LoadAction.run();
}) ;
$(document).ready(function(){
    var systemMenuShow = false ;
    $('#systemMenuButton').click(function(){
        if( systemMenuShow ) {
            $('#mainmenu').slideUp();
            systemMenuShow = false ;
            $(this).val(' +系统菜单 ')
        }
        else {
            $('#mainmenu').slideDown();
            systemMenuShow = true ;
            $(this).val(' -系统菜单 ')
        }
    });
    $('#systemCloseButton').click(function(){
        window.location.href='native://close';
    });
    SeedAction.infoUrl = '<?php echo $this->createUrl('/seed/detail');?>';
    <?php if(!empty(Yii::app()->params['globalMessage'])) echo 'GlobalMessage.init('.json_encode(Yii::app()->params['globalMessage']).')';?>
});

//-->
</script>
    </body>
</html>
