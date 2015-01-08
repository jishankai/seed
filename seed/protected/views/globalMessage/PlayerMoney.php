<div class="frame b_frame a_frame01">
    <a href="#" class="a_btn04" onclick="CommonDialog.close('EXCEPTION_TYPE_MONEY_NOT_ENOUGH');"></a>
    <p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('View', 'window prompt'); ?></span></p>
    <p class="tx01 a_color_red"><img src="themes/images/imga/a_ico51.png" alt=""><?php echo Yii::t('View', 'unable to purchase'); ?></p>
    <div class="a_button01" onclick="Common.goUrl('<?php echo $this->createUrl('shop/index', array('category' => 4)); ?>')">
        <div class="vm">
            <span class="a_button01bg2"></span><?php echo Yii::t('View', 'buy the game money'); ?>
        </div>
    </div>
</div>
<script language="javascript">
    $('.closeCurrent').click(function(){
        CommonDialog.close('EXCEPTION_TYPE_MONEY_NOT_ENOUGH');
        return false ;
    });
</script>

