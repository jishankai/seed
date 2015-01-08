<?php if ($isOk) { ?>
    <?php if (isset($reward)) { ?>
        <div class="a_frame03" style="margin-top: 88px;">
            <div class="b_text04"><?php echo Yii::t('Reward', 'login reward window title'); ?></div>
            <em><?php echo Yii::t('Reward', 'get reward successd'); ?></em>
            <?php if ($reward['type'] == 'gold') { ?>
            <i><span class="yezi"><?php echo $getInfo['gold']; ?></span></i>    
            <?php } else if ($reward['type'] == 'money') { ?>
            <i><span class="jinzz"><?php echo $reward['money']; ?></span></i>    
            <?php } else if ($reward['type'] == 'seed') { ?>
                <?php $seed = Yii::app()->objectLoader->load('Seed', $getInfo['seedId']); ?>
                <div class="tx02">
                    <em>            
                        <div id="seedImageArea<?php echo $seed->seedId; ?>" style="float: left;margin-left: 30px;margin-top: 35px;">
                        </div>
                    </em>
                    <span><?php echo $seed->name; ?></span>
                    <script>
                        SeedUnit("seedImageArea<?php echo $seed->seedId; ?>",<?php echo $seed->getDisplayData(); ?>,0.3); 
                    </script>
                </div>
            <?php } else if ($reward['type'] == 'useItem' || $reward['type'] == 'resItem' || $reward['type'] == 'decoItem') { ?>
                <?php $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $reward['id']); ?>
                <div class="tx02">
                    <em><img src="<?php echo $itemMeta->getImagePath(); ?>" height="52" width="53" alt=""/></em><span><?php echo $itemMeta->getName(); ?></span>
                </div>
            <?php } ?>
            <div class="b_btnlist02">
                <a href="#" onclick="NativeApi.close();" class="b_btn_07 a_btn_02">OK</a>
            </div>
        </div>
    <?php } ?>
<?php } else { ?>
    <div class="a_frame03" style="margin-top: 88px;">
        <em><?php echo $error; ?></em>
        <div class="b_btnlist02">
            <a href="#" onclick="NativeApi.close();" class="b_btn_07 a_btn_02">OK</a>
        </div>
    </div>
<?php } ?>