<div class="frame b_frame a_frame01">
    <a href="#" class="a_btn04" onclick="CommonDialog.close('itemFullDialog');"></a>
    <p class="a_bar1bg"><span class="a_bar1">ヒント</span></p>
    <p class="tx01 a_color_red">
        <?php
        if ($type == 'mapNotice') {
            echo Yii::t('View', 'no place add item,please clear up2');
        } else {
            echo Yii::t('View', 'no place add item,please clear up');
        }
        ?>
    </p>
    <?php if ($type == 'resItem') { ?>
        <a href="#" onclick="Common.goUrl('<?php echo $this->createUrl('/items/resShow&category=1'); ?>')">
            <div class="a_button01">
                <div class="vm">
                    <span class="a_button01bg1"></span><?php echo Yii::t('View', 'clear up'); ?>
                </div>
            </div>
        </a>
    <?php } else if ($type == 'useItem') { ?>
        <a href="#" onclick="Common.goUrl('<?php echo $this->createUrl('/items/itemShow&category=0'); ?>')">
            <div class="a_button01">
                <div class="vm">
                    <span class="a_button01bg1"></span><?php echo Yii::t('View', 'clear up'); ?>
                </div>
            </div>
        </a>
    <?php } else if ($type == 'mapNotice') { ?>
        <a href="#" onclick="Common.goUrl('<?php echo $this->createUrl('/items/resShow&category=1'); ?>')">
            <div class="a_button01">
                <div class="vm">
                    <span class="a_button01bg1"></span><?php echo Yii::t('View', 'clear up'); ?>
                </div>
            </div>
        </a>
    <?php } ?>
</div>