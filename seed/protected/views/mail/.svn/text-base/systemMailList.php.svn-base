<?php $count = 0; ?>
<?php foreach ($allMails as $mail) { ?>
    <?php $count++; ?>
    <?php if ($mail->informType == 1) { ?>
        <a href="#" onclick="getViewFile(1,'systemMail',<?php echo $mail->mailId; ?>)">
            <li id="li_<?php echo $mail->mailId; ?>" class="mailList" mailId="<?php echo $mail->mailId; ?>" onclick="showClass('li_'+<?php echo $mail->mailId; ?>)">
                <i></i><i></i>
                <div class="img_seed"><img src="themes/img/pic_15.png" alt=""></div>
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
<input type="hidden" name="mailCount" id="mailCount" value="<?php echo $count; ?>">
<div style="height:138px;"></div>