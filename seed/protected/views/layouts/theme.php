<?php
$version = SeedConfig::getGameVersion();
$size = SeedConfig::getSize();
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport" content="initial-scale=<?php echo $size; ?>, maximum-scale=<?php echo $size; ?>, user-scalable=0;" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<title></title>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.5.2.min.js?v=<?php echo $version; ?>"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js?v=<?php echo $version; ?>"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/flipsnap.js?v=<?php echo $version; ?>"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/iscroll.js?v=<?php echo $version; ?>"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/reset-min.css?v=<?php echo $version; ?>" />
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/page-min.css?v=<?php echo $version; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/basic.css?v=<?php echo $version; ?>" />
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/themes/js/app.global.js?v=<?php echo $version; ?>"></script>
</head>
<body onselectstart="return false" oncopy="return false;" oncut="return false;">
    <div class="container" id="page" style="display:none;">

        <?php echo $content; ?>

    </div><!-- page -->

<?php
//iPod; CPU iPhone OS 5_0_1 like Mac OS X
$userAgent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
$isPc = strpos($userAgent,'iPod')===false && 
        strpos($userAgent,'iPhone')===false && 
        strpos($userAgent,'Mac')===false ;
if( $isPc )  {
    ?>
<div id="header" style="clear:both; width:100%;">
    <div id="logo"> 
        <?php if( !empty($this->playerId) ) {
            $player = Yii::app()->objectLoader->load('Player', $this->playerId);
            echo '<div class="fl w40">ID:'.$this->playerId.'</div>';
            ?>
        <?php } ?>
    </div> 
</div><!-- header -->

<div id="mainmenu"><br />
    <?php
        $this->widget('zii.widgets.CMenu', array(
            'items' => array(
                array('label' => '用户设置', 'url' => array('/player/setting')),
                array('label' => '玩家信息', 'url' => array('/player/getIndex')),
                array('label' => '花园列表', 'url' => array('/garden/gardenList')),
                array('label' => '好友列表', 'url' => array('/friend/index')),
                array('label' => '地图探索', 'url' => array('/map/index')),
                array('label' => '仓库信息', 'url' => array('/items/decoShow')),
                array('label' => '道具信息', 'url' => array('/items/resShow')),
                array('label' => '商店', 'url' => array('/shop/index')),
                array('label' => '图鉴', 'url' => array('/catalog')),
                array('label' => '邮件信息', 'url' => array('/mail/mailShow')),
                array('label' => '任务列表', 'url' => array('/mission/index')),
                array('label' => '成就列表', 'url' => array('/achieve/index')),
            ),
        ));
     ?>
    <div style="clear:both;"></div>
</div><!-- mainmenu -->
 <?php
 }
 ?>

<div class="guide_main_cover"></div>
<script type="text/javascript">
<!--
$(window).load( function(){
    //$('#loading_bg').hide();
    $('#page').show();
    LoadAction.run();
}).unload(function(){
    $('#page').empty();
}) ;
LoadAction.push(function(){
    <?php if(!empty(Yii::app()->params['callback'])) { echo "NativeApi.callback(".json_encode(Yii::app()->params['callback']).");"; }?> 
});
$(document).ready(function(){
    Common.blockAHref() ;
    $('#systemCloseButton').click(function(){
        NativeApi.close();
    });
    SeedAction.infoUrl = '<?php echo $this->createUrl('/seed/detail');?>';
    $('a.a_btn04').click( function(){
        NativeApi.close();
    });
    <?php if(!empty(Yii::app()->params['globalMessage'])) { echo "GlobalMessage.init(".json_encode(Yii::app()->params['globalMessage']).");"; }?>

});
window.selectLittleGardenList.url='<?php echo $this->createUrl('Garden/LittleList'); ?>';
window.NativeApi.isPc = <?php echo $isPc?1:0;?>; 
Common.isNative = <?php echo !empty($_REQUEST['native'])?1:0; ?>;
Common.closingNative = 0 ;
Common.ajaxLoadingDelay = 500 ;
Common.isDebug = <?php echo SeedConfig::isDebug()?1:0;?> ;
Common.ajaxErrorMessage = '<?php echo Yii::t('View','an error occurred');?>';
//-->
</script>
    </body>
</html>
