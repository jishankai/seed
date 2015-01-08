<?php if (isset($_REQUEST['mailId']) && $_REQUEST['mailId'] != 0) { ?>
    <?php $mail = Yii::app()->objectLoader->load('Mail', $_REQUEST['mailId']); ?>
    <p>
        <?php if (strlen($mail->content) > 300) { ?>
            <a href="#" id="upButton"></a>
            <a href="#" id="downButton"></a>
        <?php } ?>
        <span style="float:left;" id="df">
            <?php if ($mail->goodsId != MAIL_DEFAULTVALUE) { ?>
                <?php $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mail->goodsId); ?>
                <?php echo MailModel::translateMapping($mail->mailTitle, $mail->content); ?>
            <?php } ?>
            <?php if ($mail->seedId != MAIL_DEFAULTVALUE) { ?>
                <?php $seed = Yii::app()->objectLoader->load('Seed', $mail->seedId); ?>
                <?php echo MailModel::translateMapping($mail->mailTitle, $mail->content); ?>
            <?php } ?>
            <?php if ($mail->sentGold != MAIL_DEFAULTVALUE) { ?>
                <?php echo MailModel::translateMapping($mail->mailTitle, $mail->content); ?>
            <?php } ?>
            <?php if ($mail->sentMoney != MAIL_DEFAULTVALUE) { ?>
                <?php echo MailModel::translateMapping($mail->mailTitle, $mail->content); ?>
            <?php } ?>
        </span>
    </p>
    <div class="tx01 pl10">
        <?php if ($mail->goodsId != MAIL_DEFAULTVALUE) { ?>
            <?php $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mail->goodsId); ?>
            <div class="squer"><img src="<?php echo $itemMeta->getImagePath(); ?>"/><div class="b_ico_01"><?php echo ($mail->goodsNum == 0) ? 1 : $mail->goodsNum; ?></div></div>
            <a href="#" class="a_btn03" onclick="getPresent(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_48.png"></a>
            <a href="#" class="a_btn03 a_btn03_1" onclick="delMail(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_47.png"></a>
        <?php } ?>
        <?php if ($mail->seedId != MAIL_DEFAULTVALUE) { ?>
            <?php $seed = Yii::app()->objectLoader->load('Seed', $mail->seedId); ?>
            <div class="squer">
                <div id="seedImageArea<?php echo $seed->seedId; ?>" style="float: left;margin-left: 35px;margin-top:40px;">
                </div>
                <div class="b_ico_01"><?php echo ($mail->seedNum == 0) ? 1 : $mail->seedNum; ?></div>
            </div>
            <a href="#" class="a_btn03" onclick="getSeed(<?php echo $mail->mailId; ?>,<?php echo $mail->seedId; ?>)"><img src="themes/images/imgb/pic_48.png"></a>
            <a href="#" class="a_btn03 a_btn03_1" onclick="delMail(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_47.png"></a>
            <script>
                SeedUnit("seedImageArea<?php echo $seed->seedId; ?>",<?php echo $seed->getDisplayData(); ?>,0.45); 
            </script>
        <?php } ?>
        <?php if ($mail->sentGold != MAIL_DEFAULTVALUE) { ?>
            <div class="squer"><img src="themes/images/imgb/pic_57.png"/></div>
            <a href="#" class="a_btn03" onclick="getPresent(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_48.png"></a>
            <a href="#" class="a_btn03 a_btn03_1" onclick="delMail(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_47.png"></a>
        <?php } ?>
        <?php if ($mail->sentMoney != MAIL_DEFAULTVALUE) { ?>
            <div class="squer"><img src="themes/images/imga/a_ico51.png"/></div>
            <a href="#" class="a_btn03" onclick="getPresent(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_48.png"></a>
            <a href="#" class="a_btn03 a_btn03_1" onclick="delMail(<?php echo $mail->mailId; ?>)"><img src="themes/images/imgb/pic_47.png"></a>
            <?php } ?>
    </div>
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