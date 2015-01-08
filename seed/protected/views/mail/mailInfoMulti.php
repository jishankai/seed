<div class="b_con_01 b_bg_01 a_bbg_01">
    <span class="a_bar1"><?php echo Yii::t('Mail', 'electronic mail'); ?></span>
    <i id="iconbutton1" class="a_ico1 a_ico1_c1 <?php echo $category == 1 ? 'on' : ''; ?>" onclick="goMailUrl(1)"><img src="themes/images/imgb/pic_46_2.png" alt=""><?php if ($systemMailReadFlag == true) { ?><span class="new"><?php } ?></i>
    <i id="iconbutton2" class="a_ico1 a_ico1_c1 <?php echo $category == 3 ? 'on' : ''; ?>" onclick="goMailUrl(3)"><img src="themes/images/imgb/pic_59.png" alt=""><?php if ($noticeMailReadFlag == true) { ?><span class="new"><?php } ?></i>
    <i id="iconbutton3" class="a_ico1 a_ico1_c1 <?php echo $category == 2 ? 'on' : ''; ?>" onclick="goMailUrl(2)"><img src="themes/images/imgb/pic_45_2.png" alt=""><?php if ($playerMailReadFlag == true) { ?><span class="new"><?php } ?></i>
    <?php if ($welcomeAction == true) { ?>
        <div class="a_btn04"></div>
    <?php } else { ?>
        <a href="#" class="a_btn04" onclick="NativeApi.close()"></a>
    <?php } ?>
    <?php if ($category == 1) { ?>
        <ul class="b_list_09">
            <div class="a_frame08" <?php if (!empty($allMails)) { ?> style="display:none" <?php } ?>>
                <img src="themes/img/pic_03.png" />
                <div class="quan1"><?php echo Yii::t('Mail', 'you mailList is empty'); ?></div>
            </div>
            <div id="wrapper">
                <div id="scrollElement">
                    <?php foreach ($allMails as $mail) { ?>
                        <?php if ($mail->informType == 1) { ?>
                            <a href="#" onclick="getViewFile(1,'systemMail',<?php echo $mail->mailId; ?>)">
                                <li id="li_<?php echo $mail->mailId; ?>" class="mailList" mailId="<?php echo $mail->mailId; ?>" onclick="showClass('li_'+<?php echo $mail->mailId; ?>)">
                                    <i></i><i></i>
                                    <div class="img_seed"><img src="themes/images/img/logo.png" alt=""></div>
                                    <div>
                                        <?php if ($mail->goodsId != MAIL_DEFAULTVALUE) { ?>
                                            <?php $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mail->goodsId); ?>
                                            <?php echo $itemMeta->itemObject->name; ?>
                                        <?php } ?>
                                        <?php if ($mail->seedId != MAIL_DEFAULTVALUE) { ?>
                                            <?php $seed = Yii::app()->objectLoader->load('Seed', $mail->seedId); ?>
                                            <?php echo MailModel::substr_cut($seed->getName(), 12); ?>
                                        <?php } ?>
                                        <?php if ($mail->sentGold != MAIL_DEFAULTVALUE) { ?>
                                            <?php echo Yii::t('Mail', 'gold') . $mail->sentGold; ?>
                                        <?php } ?>
                                        <?php if ($mail->sentMoney != MAIL_DEFAULTVALUE) { ?>
                                            <?php echo Yii::t('Mail', 'money') . $mail->sentMoney; ?>
                                        <?php } ?>
                                        <b class="fr"><?php echo date('Y/m/d', $mail->createTime); ?></b>
                                    </div>
                                </li>
                            </a>
                        <?php } ?>
                    <?php } ?>
                    <div class="">
                        <?php if (isset($ishiddenMoreShow) && $ishiddenMoreShow != 1) { ?>
                            <a href="#" class="a_btn1 mt20" onclick="showMore(<?php echo $category; ?>);"><?php echo Yii::t('Mail', 'showMore'); ?></a>
                        <?php } ?>
                    </div>
                    <input type="hidden" name="moreViewNum" id="moreViewNum" value="<?php echo $moreViewNum; ?>">
                    <input type="hidden" name="littleViewNum" id="littleViewNum" value="<?php echo $littleViewNum; ?>">
                    <input type="hidden" name="mailCount" id="mailCount" value="">
                    <div style="height:138px;"></div>
                </div>
            </div>
        </ul>
        <!--右-列表-->
        <div class="b_con_13" id="viewFile">
        </div>
    <?php } ?>
    <?php if ($category == 2) { ?>
        <ul class="b_list_09">
            <div class="a_frame08" <?php if (!empty($allMails)) { ?> style="display:none" <?php } ?>>
                <img src="themes/img/pic_03.png" />
                <div class="quan1"><?php echo Yii::t('Mail', 'you mailList is empty'); ?></div>
            </div>
            <div id="wrapper">
                <div id="scrollElement">
                    <?php foreach ($allMails as $mail) { ?>
                        <?php if ($mail->informType == 2) { ?>
                            <a href="#" onclick="getViewFile(2,'playerMail',<?php echo $mail->mailId; ?>)">
                                <li id="li_<?php echo $mail->mailId; ?>" onclick="showClass('li_'+<?php echo $mail->mailId; ?>)">
                                    <?php $isVisualFriend = in_array($mail->fromId, array(VISUALFRIEND_LP, VISUALFRIEND_HY, VISUALFRIEND_LM, VISUALFRIEND_HE, VISUALFRIEND_ATM)); ?>
                                    <?php if ($isVisualFriend) { ?>
                                        <?php $playerName = VisualFriend::getVisualPlayerNameById($mail->fromId); ?>
                                    <?php } else { ?>
                                        <?php $player = Yii::app()->objectLoader->load('Player', $mail->fromId); ?>
                                        <?php $playerName = $player->playerName; ?>
                                    <?php } ?>
                                    <?php if ($mail->goodsId != MAIL_DEFAULTVALUE && $mail->fromId != MAIL_DEFAULTVALUE) { ?>
                                        <?php $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mail->goodsId); ?>
                                        <div class="img_seed">    
                                            <img src="<?php echo $itemMeta->getImagePath(); ?>" alt=""/>   
                                        </div>    
                                        <div>
                                            <span class="name"><?php echo Yii::t('Mail', 'name'); ?>：<?php echo MailModel::substr_cut($playerName, 8); ?></span>
                                            <span class="time">
                                                <?php if ($mail->getDays != 0 && ($mail->getDays - time()) > 0) { ?>
                                                    <strong id="surplusTime<?php echo $mail->mailId; ?>"><?php echo Yii::t('Mail', 'wait time'); ?><strong id="lxftime<?php echo $mail->mailId; ?>" ></strong></strong>
                                                <?php } ?>
                                                <em class="fr"><?php echo date('Y/m/d', $mail->createTime); ?></em>
                                            </span>
                                            <span class="date"><?php echo Yii::t('Mail', 'days Remaining'); ?><strong><?php if ((($mail->keepDays - time()) % 86400) > 0) { ?><?php echo ((int) (($mail->keepDays - time()) / 86400)) + 1; ?><?php } else { ?><?php echo (int) (($mail->keepDays - time()) / 86400); ?><?php } ?></strong><?php echo Yii::t('Mail', 'day'); ?></span>
                                            <span id="keeptime<?php echo $mail->mailId; ?>" style="display:none">
                                        </div>
                                    <?php } ?>
                                    <?php if ($mail->seedId != MAIL_DEFAULTVALUE && $mail->fromId != MAIL_DEFAULTVALUE) { ?>
                                        <?php $seed = Yii::app()->objectLoader->load('Seed', $mail->seedId); ?>
                                        <div class="img_seed">
                                            <div id="seedImageArea2<?php echo $seed->seedId; ?>" style="float:left;margin-left:43px; margin-top: 30px;"></div>
                                        </div>
                                        <div>
                                            <span class="name"><?php echo Yii::t('Mail', 'name'); ?>：<?php echo MailModel::substr_cut($playerName, 8); ?></span>
                                            <span class="time">
                                                <?php if ($mail->getDays != 0 && ($mail->getDays - time()) > 0) { ?>
                                                    <strong id="surplusTime<?php echo $mail->mailId; ?>"><?php echo Yii::t('Mail', 'wait time'); ?><strong id="lxftime<?php echo $mail->mailId; ?>" ></strong></strong>
                                                <?php } ?>
                                                <em class="fr"><?php echo date('Y/m/d', $mail->createTime); ?></em>
                                            </span>
                                            <span class="date"><?php echo Yii::t('Mail', 'days Remaining'); ?><strong><?php if ((($mail->keepDays - time()) % 86400) > 0) { ?><?php echo ((int) (($mail->keepDays - time()) / 86400)) + 1; ?><?php } else { ?><?php echo (int) (($mail->keepDays - time()) / 86400); ?><?php } ?></strong><?php echo Yii::t('Mail', 'day'); ?></span>
                                            <span id="keeptime<?php echo $mail->mailId; ?>" style="display:none">
                                        </div>
                                        <script>
                                            SeedUnit("seedImageArea2<?php echo $seed->seedId; ?>",<?php echo $seed->getDisplayData(); ?>,0.4); 
                                        </script>
                                    <?php } ?>
                                    <?php if ($mail->sentGold != MAIL_DEFAULTVALUE && $mail->fromId != MAIL_DEFAULTVALUE) { ?>
                                        <div class="img_seed">
                                            <img src="themes/img/1.png" alt=""/>
                                        </div>
                                        <div>
                                            <span class="name"><?php echo Yii::t('Mail', 'name'); ?>：<?php echo MailModel::substr_cut($playerName, 8); ?></span>
                                            <span class="time">
                                                <?php if ($mail->getDays != 0 && ($mail->getDays - time()) > 0) { ?>
                                                    <strong id="surplusTime<?php echo $mail->mailId; ?>"><?php echo Yii::t('Mail', 'wait time'); ?><strong id="lxftime<?php echo $mail->mailId; ?>" ></strong></strong>
                                                <?php } ?>
                                                <em class="fr"><?php echo date('Y/m/d', $mail->createTime); ?></em>    
                                            </span>
                                            <span class="date"><?php echo Yii::t('Mail', 'days Remaining'); ?><strong><?php if ((($mail->keepDays - time()) % 86400) > 0) { ?><?php echo ((int) (($mail->keepDays - time()) / 86400)) + 1; ?><?php } else { ?><?php echo (int) (($mail->keepDays - time()) / 86400); ?><?php } ?></strong><?php echo Yii::t('Mail', 'day'); ?></span>
                                            <span id="keeptime<?php echo $mail->mailId; ?>" style="display:none">
                                        </div>
                                    <?php } ?>
                                    <?php if ($mail->sentMoney != MAIL_DEFAULTVALUE && $mail->fromId != MAIL_DEFAULTVALUE) { ?>
                                        <div class="img_seed">
                                            <img src="themes/images/imgb/pic_57.png" alt=""/>
                                        </div>
                                        <div>
                                            <span class="name"><?php echo Yii::t('Mail', 'name'); ?>：<?php echo MailModel::substr_cut($playerName, 8); ?></span>
                                            <span class="time">
                                                <?php if ($mail->getDays != 0 && ($mail->getDays - time()) > 0) { ?>
                                                    <strong id="surplusTime<?php echo $mail->mailId; ?>"><?php echo Yii::t('Mail', 'wait time'); ?><strong id="lxftime<?php echo $mail->mailId; ?>" ></strong></strong>
                                                <?php } ?>
                                                <em class="fr"><?php echo date('Y/m/d', $mail->createTime); ?></em>    
                                            </span>
                                            <span class="date"><?php echo Yii::t('Mail', 'days Remaining'); ?><strong><?php if ((($mail->keepDays - time()) % 86400) > 0) { ?><?php echo ((int) (($mail->keepDays - time()) / 86400)) + 1; ?><?php } else { ?><?php echo (int) (($mail->keepDays - time()) / 86400); ?><?php } ?></strong><?php echo Yii::t('Mail', 'day'); ?></span>
                                            <span id="keeptime<?php echo $mail->mailId; ?>" style="display:none">
                                        </div>
                                    <?php } ?>
                                </li>
                            </a>
                        <?php } ?>
                    <?php } ?>
                    <?php foreach ($allMails as $mail) { ?>
                        <?php if ($mail->informType == 2) { ?>
                            <script>SeedTimer.start('lxftime<?php echo $mail->mailId; ?>',<?php echo $mail->getDays - time(); ?>,function(){clockTimeEnd(<?php echo $mail->mailId; ?>);});</script>
                            <script>SeedTimer.start('keeptime<?php echo $mail->mailId; ?>',<?php echo $mail->keepDays - time(); ?>,function(){keepClockTimeEnd(<?php echo $mail->mailId; ?>);});</script>
                        <?php } ?>
                    <?php } ?>
                    <input type="hidden" name="littleViewNum" id="littleViewNum" value="<?php echo $littleViewNum; ?>">
                    <input type="hidden" name="mailCount" id="mailCount" value="">
                    <div style="height:138px;"></div>
                </div>
            </div>
        </ul>
        <!--右-列表-->
        <div class="b_con_13 a_con_13" id="viewFile">
        </div>
    <?php } ?>
    <?php if ($category == 3) { ?>
        <ul class="b_list_09">
            <?php if (empty($allNotices)) { ?>
                <div class="a_frame08" <?php if (!empty($allNotices)) { ?> style="display:none" <?php } ?>>
                    <img src="themes/img/pic_03.png" />
                    <div class="quan1"><?php echo Yii::t('Mail', 'you mailList is empty'); ?></div>
                </div>
            <?php } ?>
            <div id="wrapper">
                <div id="scrollElement">
                    <?php foreach ($allNotices as $notice) { ?>
                        <a href="#" onclick="getViewFile(3,'systemNotice',<?php echo $notice->noticeId; ?>)">
                            <li id="li_n<?php echo $notice->noticeId; ?>" class="" onclick="showClass('li_n'+<?php echo $notice->noticeId; ?>)"><i></i><i></i>
                                <div class="img_seed">
                                    <img src="themes/images/img/logo.png" alt="">
                                </div>
                                <?php echo MailModel::substr_cut($notice->title); ?>
                                <b class="fr"><?php echo date('Y/m/d', $notice->startTime); ?></b>
                            </li>
                        </a>
                    <?php } ?>
                    <div class="">
                        <?php if (isset($ishiddenMoreShow) && $ishiddenMoreShow != 1) { ?>
                            <a href="#" class="a_btn1 mt20" onclick="showMore(<?php echo $category; ?>);"><?php echo Yii::t('Mail', 'showMore'); ?></a>
                        <?php } ?>
                    </div>
                    <input type="hidden" name="moreViewNum" id="moreViewNum" value="<?php echo $moreViewNum; ?>">
                    <input type="hidden" name="littleViewNum" id="littleViewNum" value="<?php echo $littleViewNum; ?>">
                    <input type="hidden" name="mailCount" id="mailCount" value="">
                    <div style="height:138px;"></div>
                </div>
            </div>
        </ul>
        <!--右-列表-->
        <div class="b_con_13" id="viewFile">
        </div>
    <?php } ?>
</div>
<script>
    var maxSum;
    var countSum;
    var countIndex;
    var countNum;
    var wrapHeight;
    var wrapScoll;
    var barScoll;
    var barHeight;
    
<?php if ($this->actionType == REQUEST_TYPE_AJAX) { ?>
        loaded();
<?php } else { ?>
        LoadAction.push( function(){
            loaded();
        });
<?php } ?>
    
    var myScroll;
    function loaded() {
        myScroll = new iScroll("wrapper",{checkDOMChanges: true,hScrollbar:false, vScrollbar:false});
    }
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
    
    function goMailUrl(category)
    {   
        ajaxLoader.get('<?php echo $this->createUrl('Mail/MailShow'); ?>&category='+category+'&isAjaxRefresh=1',goMailUrlCallback);
    }
    function goMailUrlCallback(data)
    {
        $("#page").html(data);
    }
    function showClass(id)
    {
        $("li").removeClass("on");
        $("#"+id).addClass("on");
    }
    function getPresent(mailId)
    {
        if(typeof($('#moreViewNum').attr('value'))=='undefined')
        {
            ajaxLoader.get('<?php echo $this->createUrl('Mail/GetPresent'); ?>&mailId='+mailId+'&category=<?php echo $category; ?>',refreshGetMail);   
        }else
        {
            ajaxLoader.get('<?php echo $this->createUrl('Mail/GetPresent'); ?>&mailId='+mailId+'&category=<?php echo $category; ?>&moreViewNum='+$('#moreViewNum').val(),refreshGetMail);
        }
    }
    function refreshGetMail(data)
    {
        CommonDialog.alertDisappear('<?php echo Yii::t('Mail', 'mail get successful'); ?>');
        $("#viewFile").html('');
        $("#viewFile").html('');
        $("#scrollElement").html(data);
        
        if($("#mailCount").val()==0)
        {
            $(".a_frame08").show();
<?php if ($welcomeAction == true) { ?>
                WelcomeGuide.step = 7;
                WelcomeGuide.nextStep();
<?php } ?>
        }else
        {
<?php if ($welcomeAction == true) { ?>
                WelcomeGuide.step = 5;
                WelcomeGuide.nextStep();
<?php } ?>
        }
    }
    function refreshMail(data)
    {
        $("#viewFile").html('');
        $("#viewFile").html('');
        $("#scrollElement").html(data);
        if($("#mailCount").val()==0)
        {
            $(".a_frame08").show();
<?php if ($welcomeAction == true) { ?>
                WelcomeGuide.step = 7;
                WelcomeGuide.nextStep();
<?php } ?>
        }
    }
    function getLittleSeed(mailId,seedId)
    {
        ajaxLoader.get('<?php echo $this->createUrl('Mail/GetSeed'); ?>&mailId='+mailId+'&seedId='+seedId,refreshMail);
    }
    function getSeed(mailId,seedId)
    {
        window.selectLittleGardenList.mailId = mailId;
        window.selectLittleGardenList.seedId = seedId;
        window.selectLittleGardenList.url='<?php echo $this->createUrl('Garden/LittleList'); ?>';
        window.selectLittleGardenList.show(getSeedCallback,'window');
<?php if ($welcomeAction == true) { ?>
            var checkGardenTimes = 0 ;
            var checkGardenWindow = setInterval(function(){
                checkGardenTimes ++ ;
                if( $('#'+selectLittleGardenList.dialogId).attr('id')!=selectLittleGardenList.dialogId||checkGardenTimes>30 ){
                    return false ;
                }
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:20px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:120px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:190px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:260px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:330px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:400px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:470px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:540px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:610px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:680px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:750px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="star" style="position:absolute; left:820px; top:30px;" onclick="WelcomeGuide.nextStep()"></em>');
                $('.b_imgli01').first().append('<em class="fangxiang new_top" style="position:absolute; left:300px; top:130px;"></em>');
                $('.b_imgli01').first().append('<div id="handArea" style="position:absolute; left:300px; top:0px;"><em class="new_hand_04"><span class="new_hand1"></span><span class="new_hand2"></span></em></div>');
                $('#littleGardenCloseBtn').unbind();
                clearInterval(checkGardenWindow);
            },200)
<?php } ?>
    }
    function getSeedCallback()
    {
        var mailId = window.selectLittleGardenList.mailId;
        var seedId = window.selectLittleGardenList.seedId;
        var gardenId = window.selectLittleGardenList.selectId;
        if(typeof($('#moreViewNum').attr('value'))=='undefined')
        {
            ajaxLoader.get('<?php echo $this->createUrl('Mail/GetSeed'); ?>&mailId='+mailId+'&seedId='+seedId+'&gardenId='+gardenId+'&category=<?php echo $category; ?>',refreshGetSeed);    
        }else
        {
            ajaxLoader.get('<?php echo $this->createUrl('Mail/GetSeed'); ?>&mailId='+mailId+'&seedId='+seedId+'&gardenId='+gardenId+'&category=<?php echo $category; ?>&moreViewNum='+$('#moreViewNum').val(),refreshGetSeed);
        }
    }
    function refreshGetSeed(data)
    {  
        refreshGetMail(data);
    
        window.selectLittleGardenList.close();
    }
    function delMail(mailId)
    {
        CommonDialog.confirm('<?php echo Yii::t('Mail', 'Do you want to delete the mail?'); ?>',function(){ 
            if(typeof($('#moreViewNum').attr('value'))=='undefined')
            {
                ajaxLoader.get('<?php echo $this->createUrl('Mail/DelMail'); ?>&mailId='+mailId+'&category=<?php echo $category; ?>',refreshMail);
            }else
            {
                ajaxLoader.get('<?php echo $this->createUrl('Mail/DelMail'); ?>&mailId='+mailId+'&category=<?php echo $category; ?>&moreViewNum='+$('#moreViewNum').val(),refreshMail);
            }
        });
    }
    function tohistory()
    {
        CommonDialog.confirm('<?php echo Yii::t('Mail', 'Do you want Backup the old mail to history table'); ?>',function(){   
            ajaxLoader.get('<?php echo $this->createUrl('Mail/ToHistory'); ?>',tohistoryCallback);
        });
    }
    function tohistoryCallback(data)
    {
        if(data.isOk==true)
        {
            CommonDialog.alert('<?php echo Yii::t('Mail', 'Expired mail into history table success!'); ?>');
        }
    }
    function useStamp(mailId)
    {
        window.stampShow.mailId = mailId;
        window.stampShow.url = '<?php echo $this->createUrl('Mail/StampNotice') ?>&type=1';
        window.stampShow.show(useStampCallback);
    }
    function useStampCallback()
    {
        var playerMailInfo = window.stampShow.playerMailInfo;
        var getDaysView = window.stampShow.getDaysView;
        $('#viewFile').html(playerMailInfo);
        $('#surplusTime'+window.stampShow.mailId).html(getDaysView);
    }
    function getViewFile(category,type,id)
    {
        if(type == 'systemMail')
        {
            ajaxLoader.get('<?php echo $this->createUrl('Mail/MailRefresh'); ?>&category='+category+'&mailId='+id,function(data){$('#viewFile').html(data);});
        }
        if(type == 'systemNotice')
        {
            ajaxLoader.get('<?php echo $this->createUrl('Mail/MailRefresh'); ?>&category='+category+'&noticeId='+id,function(data){$('#viewFile').html(data);});
        }
        if(type == 'playerMail')
        {
            ajaxLoader.get('<?php echo $this->createUrl('Mail/MailRefresh'); ?>&category='+category+'&mailId='+id,function(data){$('#viewFile').html(data);});
        }
    }
    function clockTimeEnd(id)
    {
        ajaxLoader.get('<?php echo $this->createUrl('Mail/MailRefresh'); ?>&category='+2+'&mailId='+id,function(data){
            $('#surplusTime'+id).remove();
            if(typeof($('#hiddenMailId').attr('value'))=='undefined')
            {
                $('#viewFile').html(data);
            }else
            {
                if($('#hiddenMailId').attr('value')==id)
                {
                    $('#viewFile').html(data);
                }
            }
        });
    }
    function keepClockTimeEnd(mailId)
    {
        if(typeof($('#moreViewNum').attr('value'))=='undefined')
        {
            ajaxLoader.get('<?php echo $this->createUrl('Mail/DelKeepDays'); ?>&mailId='+mailId+'&category=<?php echo $category; ?>',refreshKeepMail);
        }else
        {
            ajaxLoader.get('<?php echo $this->createUrl('Mail/DelKeepDays'); ?>&mailId='+mailId+'&category=<?php echo $category; ?>&moreViewNum='+$('#moreViewNum').val(),refreshKeepMail);
        }
        
        if($('#hiddenMailId').attr('value') == mailId)
        {
            $('#viewFile').html('');
        }
    }
    function refreshKeepMail(data)
    {
        $("#viewFile").html('');
        $('#scrollElement').html(data);
    }
    function showMore(category)
    {   
        ajaxLoader.get('<?php echo $this->createUrl('Mail/MailShow'); ?>&moreAction=0&category='+category+'&moreViewNum='+$('#moreViewNum').val(),showMoreCallback);
    }
    function showMoreCallback(data)
    {
        $('#scrollElement').html(data);
    }
</script>
<?php if ($welcomeAction == true) { ?>
    <div id="leftTip" class="b_text06" style="position:absolute;bottom:-60px;left:-60px;">
        <i class="text06pic"></i>
        <div class="long">
            <span id="welcomeMessage"></span>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
    <div id="rightTip" class="b_text06 b_text06_1" style="position:absolute;bottom:-60px;right:-80px;">
        <i class="text06pic"></i>
        <div class="long">
            <span id="welcomeMessage2"></span>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
    <script language="javascript">
        var WelcomeMailId;
        var WelcomeSeedId;
        $(document).ready(function(){
    <?php if (isset($allMails)) { ?>
        <?php foreach ($allMails as $mail) { ?>
                        WelcomeMailId = <?php echo $mail->mailId; ?>;
                        WelcomeSeedId = <?php echo $mail->seedId; ?>;
            <?php break; ?>
        <?php } ?>
    <?php } ?>
        });
        LoadAction.push(function(){
            $('div.guide_main_cover').show().css('z-index',100); 
        });
        var WelcomeGuide = {
            step : 0 ,
            zIndex : 101 ,
            nextStep : function(){
                this.step ++ ;
                switch( this.step ) {
                    case 1 :
                        $('.b_con_01').append('<div class="b_text07 left" style="position:absolute; top:50px; left:530px;"><em class="star"></em></div>')
                        $('.b_con_01').append('<div class="b_text07 left" style="position:absolute; top:50px; left:630px;"><em class="star"></em></div>')
                        $('.b_con_01').append('<div class="b_text07 left" style="position:absolute; top:50px; left:740px;"><em class="star"></em></div>')
                        $('.b_text07').css('z-index',this.zIndex++);
                        $('#leftTip').hide();
                        $('#rightTip').show();
                        $('.b_text06').css('z-index',this.zIndex++);
                        $('.b_text06').bind("click",function(){WelcomeGuide.nextStep();});
                        var data = "<?php echo Yii::t('GuideMessage', 'message_31_01'); ?>";
                        this.showTip2(data);
                        break ;
                    case 2 :
                        var data = "<?php echo Yii::t('GuideMessage', 'message_31_02'); ?>";
                        this.showTip2(data);
                        break ;
                    case 3 :
                        $('.b_ico01').remove();
                        $('.b_text07').remove();
                        $('.b_con_01').append('<em class="star" style="position:absolute; left:80px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                        $('.b_con_01').append('<em class="star" style="position:absolute; left:145px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                        $('.b_con_01').append('<em class="star" style="position:absolute; left:210px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                        $('.b_con_01').append('<em class="star" style="position:absolute; left:275px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                        $('.b_con_01').append('<em class="star" style="position:absolute; left:340px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                        $('.b_con_01').append('<em class="star" style="position:absolute; left:400px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                        $('.b_con_01').append('<em class="fangxiang new_left" style="position:absolute; left:510px; top:160px;"></em>');
                        $('.b_con_01').append('<div id="handArea" style="position:absolute; left:260px; top:180px;"><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em></div>');
                        $('#handArea').bind("click",function(){WelcomeGuide.nextStep();});
                        $('.star').css('z-index',this.zIndex++);
                        $('#handArea').css('z-index',this.zIndex++);
                        $('#leftTip').hide();
                        $('#rightTip').show();
                        $('.b_text06').css('z-index',this.zIndex++);
                        $('.b_text06').unbind();
                        $('.b_text05goon').remove();
                        $('.b_text07').css('top','160px');
                        $('.b_text07').css('left','200px');
                        $('.b_text07').css('z-index',this.zIndex++);
                        var data = "<?php echo Yii::t('GuideMessage', 'message_32'); ?>";
                        this.showTip2(data);
                        break ;
                    case 4 :
                        $('.star').remove();
                        $('.fangxiang').remove();
                        $('#handArea').remove();
                        $('.b_con_01').append('<div class="b_text07 left" style="position:absolute; left:705px; top:470px;"><em class="star"></em><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em><em class="fangxiang new_left"></em></div>');
                        $('.b_text07').bind("click",function(){WelcomeGuide.nextStep();});
                        $('.b_text07').css('z-index',this.zIndex++);
                        $('#leftTip').show();
                        $('#rightTip').hide();
                        $('.b_text06').css('z-index',this.zIndex++);
                        showClass('li_'+WelcomeMailId);
                        getViewFile(1,'systemMail',WelcomeMailId);
                        var data = "<?php echo Yii::t('GuideMessage', 'message_32'); ?>";
                        this.showTip(data);
                        break ;
                    case 5 :
                        $('.b_text07').remove();
                        if(WelcomeSeedId == 0)
                        { 
                            getPresent(WelcomeMailId);
                            var data = "<?php echo Yii::t('GuideMessage', 'message_34_01'); ?>";
                            this.showTip(data);
                            this.nextStep();
                        }else
                        {
                            $('#leftTip').hide();
                            $('#rightTip').show();
                            $('.b_text06').css('z-index',2000);
                            getSeed(WelcomeMailId,WelcomeSeedId);
                            var data = "<?php echo Yii::t('GuideMessage', 'message_34_02'); ?>";
                            this.showTip2(data);
                        }
                        break ;
                    case 6 :
                        $('.star').remove();
                        $('.fangxiang').remove();
                        $('#handArea').remove();
                        ajaxLoader.get('<?php echo $this->createUrl('guide/saveStatus&accessLevel=31&noState=1'); ?>',function(){});
                        $('#leftTip').hide();
                        $('#rightTip').hide();
                        $('.b_text05goon').remove();
                        $('.b_text06').css('z-index',this.zIndex++);
                        $('.b_text06').bind("click",function(){$(".b_text06").hide();});
    <?php if ($category == 1) { ?>
                            $('.b_con_01').append('<em class="star" style="position:absolute; left:80px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                            $('.b_con_01').append('<em class="star" style="position:absolute; left:145px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                            $('.b_con_01').append('<em class="star" style="position:absolute; left:210px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                            $('.b_con_01').append('<em class="star" style="position:absolute; left:275px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                            $('.b_con_01').append('<em class="star" style="position:absolute; left:340px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                            $('.b_con_01').append('<em class="star" style="position:absolute; left:400px; top:160px;" onclick="WelcomeGuide.nextStep()"></em>');
                            $('.b_con_01').append('<em class="fangxiang new_left" style="position:absolute; left:510px; top:160px;"></em>');
                            $('.b_con_01').append('<div id="handArea" style="position:absolute; left:260px; top:180px;"><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em></div>');
                            $('#handArea').bind("click",function(){WelcomeGuide.nextStep();});
                            $('.star').css('z-index',this.zIndex++);
                            $('#handArea').css('z-index',this.zIndex++);
    <?php } ?>
                        break ;
                    case 7 :
                        $('.star').remove();
                        $('.fangxiang').remove();
                        $('#handArea').remove();
                        $('div.guide_main_cover').hide();
                        WelcomeMailId = $('.mailList').first().attr('mailId');
                        showClass('li_'+WelcomeMailId);
                        getViewFile(1,'systemMail',WelcomeMailId);
                        break ;
                    case 8 :
                        $('.star').remove();
                        $('.fangxiang').remove();
                        $('#handArea').remove();
                        ajaxLoader.get('<?php echo $this->createUrl('guide/saveStatus&accessLevel=32'); ?>',function(){});
                        //$('div.guide_main_cover').show();
                        $('.b_con_01').append('<div class="b_text07 left" style="position:absolute; left:885px; top:65px;"><em class="star"></em><em class="new_hand"><span class="new_hand1"></span><span class="new_hand2"></span></em></div>');
                        $('.b_text07').bind("click",function(){WelcomeGuide.nextStep();});
                        $('.b_text07').css('z-index',this.zIndex++);
                        $('#leftTip').remove();
                        $('#rightTip').show();
                        $('.b_text06').css('z-index',this.zIndex++);
                        $('.b_text06').unbind();
                        var data = "<?php echo Yii::t('GuideMessage', 'message_36'); ?>";
                        this.showTip2(data);
                        break ;
                    case 9 :
                        NativeApi.delay(true);
                        ajaxLoader.get('<?php echo $this->createUrl('guide/saveStatus&accessLevel=39'); ?>',function(){
                            $("#page").empty();
                            NativeApi.close().doRequest();
                        });
                        break ;
                    default:
                        console.log(this.step);
                    }
                } ,
                showTip: function(data){
                    var self = this ;
                    $('#welcomeMessage').empty();
                    var elem = document.createElement('div');
                    $(elem).attr('style','text-align: left;');
                    $(elem).hide().html(data).fadeIn();
                    $('#welcomeMessage').append(elem);
                },
                showTip2: function(data){
                    var self = this ;
                    $('#welcomeMessage2').empty();
                    var elem = document.createElement('div');
                    $(elem).attr('style','text-align: left;');
                    $(elem).hide().html(data).fadeIn();
                    $('#welcomeMessage2').append(elem);
                }
            }

            $(document).ready(function(){
                $('#iconbutton1').attr('onclick','');
                $('#iconbutton2').attr('onclick','');
                $('#iconbutton3').attr('onclick','');
    <?php if (isset($accessLevel) && $accessLevel == 31) { ?>
                    WelcomeGuide.step = 5;
    <?php } ?>
    <?php if (isset($accessLevel) && $accessLevel == 32) { ?>
                    WelcomeGuide.step = 7;
    <?php } ?>
                WelcomeGuide.nextStep();
            }) ;
<?php } ?>
</script>