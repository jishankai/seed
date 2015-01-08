<div class="frame b_frame a_frame01">
    <a href="#" class="a_btn04" onclick="NativeApi.close();"></a>
    <p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('View', 'window prompt'); ?></span></p>
    <p class="tx01 a_color_red"><?php echo Yii::t('View', 'no place add item,please clear up'); ?></p>
    <div class="a_button01"><span class="a_button01bg1">
    <?php if($type == 'resItem'){ ?>
    <a href="#" onclick="Common.goUrl('<?php echo $this->createUrl('/items/resShow&category=1'); ?>')"></span><?php echo Yii::t('View', 'clear up'); ?></div></a>
    <?php }else if($type == 'useItem'){ ?>
    <a href="#" onclick="Common.goUrl('<?php echo $this->createUrl('/items/itemShow&category=0'); ?>')"></span><?php echo Yii::t('View', 'clear up'); ?></div></a>
    <?php }?>
</div>