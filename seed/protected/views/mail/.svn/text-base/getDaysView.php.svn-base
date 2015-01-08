<?php if ($mail->getDays - time() > 0) { ?>
    <?php echo Yii::t('Mail', 'wait time'); ?><strong id="lxftime<?php echo $mail->mailId; ?>" ><script>SeedTimer.start('lxftime<?php echo $mail->mailId; ?>',<?php echo $mail->getDays - time(); ?>,function(){getViewFile(2,'playerMail',<?php echo $mail->mailId; ?>);});</script></strong>
<?php } ?>