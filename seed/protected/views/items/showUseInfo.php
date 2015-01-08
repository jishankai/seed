<?php if (isset($useArray)) { ?>
    <div class="a_frame03">
        <center><p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('Item', 'you get the following items'); ?></span></p></center>
        <?php if (($itemId >= 39 && $itemId <= 41)) { ?>
            <div class="tx02">
                <?php foreach ($useArray as $innerItemId => $num) { ?>
                    <?php $itemInnerMeta = Yii::app()->objectLoader->load('ItemMeta', $innerItemId); ?>
                    <em><img src="<?php echo $itemInnerMeta->getImagePath(); ?>" height="52" width="53" alt=""></em>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if ($itemId == 47) { ?>
            <div class="tx02">
                <?php if (isset($useArray[0])) { ?>
                    <i><span class="yezi"><?php echo $useArray[0]['num']; ?></span></i>
                <?php } ?>
                <?php if (isset($useArray[1])) { ?>
                    <i><span class="jinzz"><?php echo $useArray[1]['num']; ?></span></i>
                <?php } ?>
                <?php if (isset($useArray[2])) { ?>
                    <?php $itemInnerMeta = Yii::app()->objectLoader->load('ItemMeta', $useArray[2]['id']); ?>
                    <?php echo $useArray[2]['name']; ?><em><img src="<?php echo $itemInnerMeta->getImagePath(); ?>" height="52" width="53" alt=""></em>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="b_btnlist02">
            <a href="#" onclick="window.getUseItemShow.over()" class="b_btn_07 a_btn_02">OK</a>
        </div>
    </div>
<?php } ?>