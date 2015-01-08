<?php $count = 0; ?>
<?php foreach ($allMails as $mail) { ?>
    <?php $count++; ?>
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
<input type="hidden" name="mailCount" id="mailCount" value="<?php echo $count; ?>">
<div style="height:138px;"></div>