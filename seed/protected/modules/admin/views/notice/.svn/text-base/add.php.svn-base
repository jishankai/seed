<meta charset="utf-8">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=0.5, maximum-scale=0.5, user-scalable=0;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/flipsnap.js"></script>
<script src="/seed/js/iscroll.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/reset-min.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/page-min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/basic.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/themes/js/app.global.js"></script>
<div class="container" id="page" style=""><div class="b_con_01 b_bg_01 a_bbg_01">
        <span class="a_bar1">メール</span>
        <a href="#" class="a_btn04"></a>
        <ul class="b_list_09">
            <div id="wrapper" style="overflow: hidden;">
                <div id="scrollElement" style="-webkit-transition: -webkit-transform 0ms; -webkit-transform-origin: 0px 0px; -webkit-transform: translate3d(0px, 0px, 0px); ">
                    <a href="#">
                        <li id="li_n7" class="on"><i></i><i></i>
                            <div class="img_seed">
                                <img src="themes/images/img/logo.png" alt="">
                            </div>
                            <strong id="mailTitle"></strong>
                            <b class="fr"></b>
                        </li>
                    </a>
                    <div class="">
                    </div>
                    <input type="hidden" name="moreViewNum" id="moreViewNum" value="20">
                    <input type="hidden" name="littleViewNum" id="littleViewNum" value="2">
                    <input type="hidden" name="mailCount" id="mailCount" value="">
                    <div style="height:138px;"></div>
                </div>
            </div>
        </ul>
        <!--右-列表-->
        <div class="b_con_13" id="viewFile">       
            <p style="height:360px;">
                <a href="#" id="upButton"></a>
                <a href="#" id="downButton"></a>
                <span style="width:360px; height:360px; float:left; overflow:hidden; ">
                    <span style="float:left;" id="df">
                        <span id="testContent">
                        </span>
                    </span>
                </span>
            </p>
            <div class="tx01 pl10">
            </div>
        </div>
    </div>
</div><!-- page -->
<div <?php
$background = ModuleUtil::loadconfig('admin', 'background');
if ($background['SHOW_BACKGROUND_COLOR'] == 1) {
   ?> style="background-color:<?php echo $background['BACKGROUND_COLOR'] ?>" <?php } ?>>
    <a href="<?php echo $this->createUrl('index'); ?>">back to index</a>
    <?php $form = $this->beginWidget('CActiveForm', array('id' => 'noticeForm',)); ?>
    <?php echo $form->errorSummary($model); ?>
    <div>
        <div><?php echo $form->labelEx($model, 'start time'); ?></div><div><?php echo $form->textField($model, 'startTime'); ?>(format:2012-12-21 00:00:00)</div>
        <div><?php echo $form->labelEx($model, 'end time'); ?></div><div><?php echo $form->textField($model, 'endTime'); ?>(format:2012-12-21 00:00:00)</div>
    </div>
    <div>
        <div><?php echo $form->labelEx($model, 'title'); ?></div><div><?php echo $form->textArea($model, 'title', array('cols' => 30, 'rows' => 3, 'style' => "overflow:auto")); ?></div>
        <div><?php echo $form->labelEx($model, 'notice'); ?></div><div><?php echo $form->textArea($model, 'notice', array('cols' => 30, 'rows' => 10, 'style' => "overflow:auto")); ?></div>
    </div>
    <div width="90%">
        <input type="button" name="reset" value="reset" onClick="EditNotice.resetData();">
        <?php echo CHtml::submitButton('submit'); ?>
        <input type="button" name="view" value="view" onClick="flush();">
    </div>
    <?php $this->endWidget(); ?>

    <!--list_05 start-->
    <ul class="list_05 bg_border_yellow a_01 mt40">
        <li class="pr">
            <a href="#">
                <div id="titlePreview1" class="p10 bold f14 ls-2" style="word-wrap:nomal;word-break:break-all;"><?php echo $model->notice; ?></div>
            </a> 
            <?php
            echo '<div class="title_02 pa t-40" style="left:-6px;">';
            echo Yii::t("View", "bulletin");
            echo '</div>';
            ?>
        </li>
        <!--list_05 end-->
    </ul>


    <div class="bg_content_yellow mh400 clearfix" style="height:435px">
        <div id="titlePreview2" class="line_03 ac lh30 bold pt10 pb10" style="word-wrap:nomal;word-break:break-all;">
            <?php echo $model->title; ?>
        </div>
        <div id="contentPreview" class="p10 lh14" style="word-wrap:nomal;word-break:break-all;">
            <?php echo $model->notice; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    window.EditNotice = {
        previewData:function() {
            document.getElementById('titlePreview1').innerHTML = document.getElementById('EditNoticeForm_titleEdit').value;
            document.getElementById('titlePreview2').innerHTML = document.getElementById('EditNoticeForm_titleEdit').value;
            document.getElementById('contentPreview').innerHTML = document.getElementById('EditNoticeForm_contentEdit').value;
        },
        resetData:function() {
            location.replace(location.href);
        }
    }
    $(document).ready(function(){
        //        $("#EditNoticeForm_title").bind("keydown",function(){flushTitle();});
        //        $("#EditNoticeForm_notice").bind("keydown",function(){flushNotice();});
        //        $("#EditNoticeForm_endTime").bind("keydown",function(){flushEndTime();});
        flush();
    });
    function flush()
    {
        flushTitle();
        flushNotice();
        flushEndTime();
    }
    function flushTitle()
    {
        var string = $("#EditNoticeForm_title").val();
        $("#mailTitle").html(string);
    }
    function flushNotice()
    {
        var string = $("#EditNoticeForm_notice").val();
        $("#testContent").html(string);
    }
    function flushEndTime()
    {
        var string = $("#EditNoticeForm_endTime").val();
        $(".fr").text(string);
    }
</script>
<script>
    $(document).ready(function(){
        var spanHeight = new Flipsnap('#df', { position: 'y',distance:Math.ceil($('#df').height()-360)/10,maxPoint:10,callback:function(){
                if(spanHeight.currentY <= spanHeight.maxY){
                    $('#upButton').hide();
                }else{
                    $('#upButton').show();
                }
                    
                if(spanHeight.currentY == 0){
                    $('#downButton').hide();
                }else{
                    $('#downButton').show();
                }
            } });
    });

    scrollElement = function( elementId,speed ){
        self = this ;
        self.lineHeight = 20 ;
        self.speed = 300;
        self.step = 2 ;
        self.elementId = elementId ;
        self.element = $('#'+elementId);
        self.timer = null ;
        self.position = 1 ;

        self.toBottom = function(){
            self.position = -1 ;
            self.timer = setInterval(self.toHeight,self.speed/self.lineHeight*self.step);
        }

        self.toTop = function() {
            self.position = 1 ;
            self.timer = setInterval(self.toHeight,self.speed/self.lineHeight*self.step);
        } 
        self.toHeight = function(){
            var newTop = Math.min(10,self.element.offset().top+self.position*self.step ) ;
            newTop = Math.max(newTop,-self.element.height());
            self.element.offset({top:newTop});
            //console.log(self.element.offset().top);
        }
        self.stop = function(){
            clearInterval( self.timer );
        }


    }
</script>