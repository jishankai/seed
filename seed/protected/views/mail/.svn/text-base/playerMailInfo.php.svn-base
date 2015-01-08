<?php if (isset($_REQUEST['mailId']) && $_REQUEST['mailId'] != 0) { ?>
    <?php $mail = Yii::app()->objectLoader->load('Mail', $_REQUEST['mailId']); ?>
    <?php $isVisualFriend = VisualFriend::isVisualFriend($this->playerId, $mail->fromId); ?>
    <?php if ($isVisualFriend) { ?>
        <?php $playerName = VisualFriend::getVisualPlayerNameById($mail->fromId); ?>
    <?php } else { ?>
        <?php $player = Yii::app()->objectLoader->load('Player', $mail->fromId); ?>
        <?php $playerName = $player->playerName; ?>
    <?php } ?>
    <input type="hidden" name="hiddenMailId" id="hiddenMailId" value="<?php echo $mail->mailId; ?>">
    <i class="b_title01">
        <em><?php echo Yii::t('Mail', 'space'); ?>:<span><?php echo $mailCount; ?>/<?php echo MAIL_MAXMAIL; ?></span></em>
        <em><?php echo Yii::t('Mail', 'ranks'); ?>:<span><?php echo $mailQueueCount; ?></span></em>
    </i>
    <?php if ($mail->goodsId != MAIL_DEFAULTVALUE) { ?>
        <?php $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mail->goodsId); ?>
        <div class="p10"><?php echo Yii::t('Mail', 'name'); ?>：<?php echo $playerName; ?> 
            <span class="fr"><?php echo date('Y/m/d', $mail->createTime); ?></span><br/>
            <?php if ($mail->keepDays > time() && $mail->isGet == 0) { ?>
                <?php echo Yii::t('Mail', 'days Remaining'); ?><span><?php if ((($mail->keepDays - time()) % 86400) > 0) { ?><?php echo ((int) (($mail->keepDays - time()) / 86400)) + 1; ?><?php } else { ?><?php echo (int) (($mail->keepDays - time()) / 86400); ?><?php } ?></span><?php echo Yii::t('Mail', 'day'); ?>
            <?php } ?><br/>
            <b class="pt10 db"><?php echo $itemMeta->itemObject->name; ?></b>
        </div>
        <div class="squer"><img src="<?php echo $itemMeta->getImagePath(); ?>"/></div>
        <div class="tx02 clearfix">
            <?php if ($mail->getDays > time() && $mail->isGet == 0) { ?>
                <span class="fl w120 pl20"><?php echo Yii::t('Mail', 'wait time'); ?><br/><span id="surplusTime_<?php echo $mail->mailId; ?>"><span id="lxftime_<?php echo $mail->mailId; ?>"></span></span>
                </span>
                <a  href="#"  onclick="useStamp(<?php echo $mail->mailId; ?>);"><img src="themes/images/imgb/pic_50.png"/></a>
            <?php } else { ?>
                <span class="fl w120 pl20"><?php echo Yii::t('Mail', 'please get'); ?></span>
            <?php } ?>
        </div>
        <div class="tx01">
            <?php if ($mail->getDays > time() && $mail->isGet == 0) { ?>
                <a href="#" class="a_btn03 ml10 op05" ><img src="themes/images/imgb/pic_48.png"></a>    
            <?php } else { ?>
                <a href="#" class="a_btn03 ml10" onclick="getPresent(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_48.png"></a>    
            <?php } ?>
            <a href="#" class="a_btn03 a_btn03_1 ml10" onclick="delMail(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_47.png"></a>
        </div>
    <?php } ?>
    <?php if ($mail->seedId != MAIL_DEFAULTVALUE) { ?>
        <?php $seed = Yii::app()->objectLoader->load('Seed', $mail->seedId); ?>
        <div class="p10"><?php echo Yii::t('Mail', 'name'); ?>：<?php echo $playerName; ?>                   
            <span class="fr"><?php echo date('Y/m/d', $mail->createTime); ?></span><br/>
            <?php if ($mail->keepDays > time() && $mail->isGet == 0) { ?>
                <?php echo Yii::t('Mail', 'days Remaining'); ?><span><?php if ((($mail->keepDays - time()) % 86400) > 0) { ?><?php echo ((int) (($mail->keepDays - time()) / 86400)) + 1; ?><?php } else { ?><?php echo (int) (($mail->keepDays - time()) / 86400); ?><?php } ?></span><?php echo Yii::t('Mail', 'day'); ?>
            <?php } ?><br/>
            <b class="pt10 db"><?php echo MailModel::substr_cut($seed->getName(), 18); ?></b>
        </div>
        <div class="squer">
            <div id="seedImageArea<?php echo $seed->seedId; ?>" style="float: left;margin-left: 35px;margin-top:40px;">
            </div>    
        </div>
        <div class="tx02 clearfix">
            <?php if ($mail->getDays > time() && $mail->isGet == 0) { ?>
                <span class="fl w120 pl20"><?php echo Yii::t('Mail', 'wait time'); ?><br/><span id="surplusTime_<?php echo $mail->mailId; ?>"><span id="lxftime_<?php echo $mail->mailId; ?>"></span></span>
                </span>
                <a  href="#"  onclick="useStamp(<?php echo $mail->mailId; ?>);"><img src="themes/images/imgb/pic_50.png"/></a>
            <?php } else { ?>
                <span class="fl w120 pl20"><?php echo Yii::t('Mail', 'please get'); ?></span>
            <?php } ?>
        </div>
        <div class="tx01">
            <?php if ($mail->getDays > time() && $mail->isGet == 0) { ?>
                <a href="#" class="a_btn03 ml10 op05" ><img src="themes/images/imgb/pic_48.png"></a>
            <?php } else { ?>
                <a href="#" class="a_btn03 ml10 <?php if ($mail->getDays > time() && $mail->isGet == 0) echo 'op05'; ?>" onclick="getSeed(<?php echo $mail->mailId; ?>,<?php echo $mail->seedId; ?>)"><img src="themes/images/imgb/pic_48.png"></a>    
            <?php } ?>
            <a href="#" class="a_btn03 a_btn03_1 ml10" onclick="delMail(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_47.png"></a>
        </div>
    <?php } ?>
    <?php if ($mail->sentGold != MAIL_DEFAULTVALUE) { ?>
        <div class="p10"><?php echo Yii::t('Mail', 'name'); ?>：<?php echo $playerName; ?>                   
            <span class="fr"><?php echo date('Y/m/d', $mail->createTime); ?></span><br/>
            <?php if ($mail->keepDays > time() && $mail->isGet == 0) { ?>
                <?php echo Yii::t('Mail', 'days Remaining'); ?><span><?php if ((($mail->keepDays - time()) % 86400) > 0) { ?><?php echo ((int) (($mail->keepDays - time()) / 86400)) + 1; ?><?php } else { ?><?php echo (int) (($mail->keepDays - time()) / 86400); ?><?php } ?></span><?php echo Yii::t('Mail', 'day'); ?>
            <?php } ?><br/>
            <b class="pt10 db"><?php echo Yii::t('Mail', 'gold') . $mail->sentGold; ?></b>
        </div>
        <div class="squer"><img src="themes/img/1.png"/><span><?php echo $mail->sentGold; ?></span></div>
        <div class="tx02 clearfix">
            <?php if ($mail->getDays > time() && $mail->isGet == 0) { ?>
                <span class="fl w120 pl20"><?php echo Yii::t('Mail', 'wait time'); ?><br/><span id="surplusTime_<?php echo $mail->mailId; ?>"><span id="lxftime_<?php echo $mail->mailId; ?>"></span></span> 
                </span>
                <a  href="#"  onclick="useStamp(<?php echo $mail->mailId; ?>);"><img src="themes/images/imgb/pic_50.png"/></a>
            <?php } else { ?>
                <span class="fl w120 pl20"><?php echo Yii::t('Mail', 'please get'); ?></span>
            <?php } ?>
        </div>
        <div class="tx01">
            <?php if ($mail->getDays > time() && $mail->isGet == 0) { ?>
                <a href="#" class="a_btn03 ml10 op05" ><img src="themes/images/imgb/pic_48.png"></a>    
            <?php } else { ?>
                <a href="#" class="a_btn03 ml10" onclick="getPresent(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_48.png"></a>    
            <?php } ?>
            <a href="#" class="a_btn03 a_btn03_1 ml10" onclick="delMail(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_47.png"></a>
        </div>
    <?php } ?>
    <?php if ($mail->sentMoney != MAIL_DEFAULTVALUE) { ?>
        <div class="p10"><?php echo Yii::t('Mail', 'name'); ?>：<?php echo $playerName; ?>                   
            <span class="fr"><?php echo date('Y/m/d', $mail->createTime); ?></span><br/>
            <?php if ($mail->keepDays > time() && $mail->isGet == 0) { ?>
                <?php echo Yii::t('Mail', 'days Remaining'); ?><span><?php if ((($mail->keepDays - time()) % 86400) > 0) { ?><?php echo ((int) (($mail->keepDays - time()) / 86400)) + 1; ?><?php } else { ?><?php echo (int) (($mail->keepDays - time()) / 86400); ?><?php } ?></span><?php echo Yii::t('Mail', 'day'); ?>
            <?php } ?><br/>
            <b class="pt10 db"><?php echo Yii::t('Mail', 'money') . $mail->sentMoney; ?></b>
        </div>
        <div class="squer"><img src="themes/images/imga/a_ico51.png"/><span><?php echo $mail->sentMoney; ?></span></div>
        <div class="tx02 clearfix">
            <?php if ($mail->getDays > time() && $mail->isGet == 0) { ?>
                <span class="fl w120 pl20"><?php echo Yii::t('Mail', 'wait time'); ?><br/><span id="surplusTime_<?php echo $mail->mailId; ?>"><span id="lxftime_<?php echo $mail->mailId; ?>"></span></span>
                </span>
                <a  href="#"  onclick="useStamp(<?php echo $mail->mailId; ?>);"><img src="themes/images/imgb/pic_50.png"/></a>
            <?php } else { ?>
                <span class="fl w120 pl20"><?php echo Yii::t('Mail', 'please get'); ?></span>
            <?php } ?>
        </div>
        <div class="tx01">
            <?php if ($mail->getDays > time() && $mail->isGet == 0) { ?>
                <a href="#" class="a_btn03 ml10 op05" ><img src="themes/images/imgb/pic_48.png"></a>
            <?php } else { ?>
                <a href="#" class="a_btn03 ml10" onclick="getPresent(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_48.png"></a>    
            <?php } ?>
            <a href="#" class="a_btn03 a_btn03_1 ml10" onclick="delMail(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_47.png"></a>
        </div>
    <?php } ?>
    <script>
        SeedTimer.start('lxftime_<?php echo $mail->mailId; ?>',<?php echo $mail->getDays - time(); ?>);
        SeedTimer.actions['lxftime<?php echo $mail->mailId; ?>'] = null ;
        SeedTimer.stop('lxftime<?php echo $mail->mailId; ?>');
        SeedTimer.start('lxftime<?php echo $mail->mailId; ?>',<?php echo $mail->getDays - time(); ?>,function(){clockTimeEnd(<?php echo $mail->mailId; ?>);});
    </script>
    <?php if ($mail->seedId != MAIL_DEFAULTVALUE) { ?>    
        <script>
            SeedUnit("seedImageArea<?php echo $seed->seedId; ?>",<?php echo $seed->getDisplayData(); ?>,0.45); 
        </script>
    <?php } ?>
<?php } ?>
<script>
    function hoverClass(){
        addHover(".b_list_03 a");
        addHover(".b_con_11 .b_btn_07");
        addHover(".b_con_13 .squer");
        addHover(".b_btn_02");
        addHover(".b_btn_03");
        addHover(".b_btn_04");
        addHover(".b_btn_05");
        addHover(".b_btn_07");
        addHover(".b_btn_08");
        addHover(".b_ico");
        addHover(".next_page");

        addHover(".next_page_left");
        addHover(".b_fsalediv .btn");
        addHover(".b_btn53");
        addHover(".b_bg01 .b_icon01");
        addHover(".b_bg01 .b_icon01.b_icon01left");
        addHover(".a");
        addHover(".a_btn1");

        addHover(".a_btn03");
        addHover(".a_btn09");
        addHover(".a_btn13");
        addHover(".a_btn14");
        addHover(".a_btn18 a");
	
        addHover(".a_btn19_1");
        addHover(".a_btn19_2");
        addHover(".a_btn19_3");
	
        addHover(".a_btn19_4");
        addHover(".a_btn20");
        addHover(".a_btn21");
        addHover(".a_btn22");

	
        addHover(".a_btn23");
        addHover(".home_icon1");
        addHover(".help_icon");
        addHover(".menu_icon1");
        addHover(".a_bar2 a.hover");
        addHover(".new_btn");
	
	
        addHover(".a_list1 ul li h3 .num01");
        addHover(".a_list1 ul li h3 .num02");
	
        addHover(".a_list1 ul li h3 .num03");
        addHover(".a_list1 ul li h3 .num04");
        addHover(".a_list1 ul li h3 .num05");
        addHover(".a_list1 ul li h3 .num06");
        addHover(".a_list1 ul li h3 .num07");
        addHover(".a_list1 ul li h3 .num08");
        addHover(".a_list1 ul li h3 .num09");
        addHover(".a_list1 ul li h3 .num10");
	
        addHover(".a_btn11");
        addHover(".a_btn04");
        addHover(".a_btn05 a");
        addHover(".a_bar3 img");
	
        addHover(".a_button01");
        addHover(".b_con_10.a_con_02 .tx02 em");
        addHover(".b_btn_07.a_btn_blue");
        addHover(".a_bg_01 .b_icobg .b_ico");
        addHover(".a_con_f01 .a_abtn1");
	
	
        addHover(".bg_001 .this_nav li");

    }

    function addHover(element){
        $(element).bind("touchstart",function(){
            $(this).addClass("hover");
        });
        $(element).bind("touchend",function(){
            $(this).removeClass("hover");
        });
    }
</script>