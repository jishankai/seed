<?php $count = 0; ?>
<?php foreach ($allNotices as $notice) { ?>
    <?php $count++; ?>
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
<input type="hidden" name="mailCount" id="mailCount" value="<?php echo $count; ?>">
<div style="height:138px;"></div>