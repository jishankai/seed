<?php if ($type == 1) { ?>
    <div class="frame b_frame02 a_frame01">
        <a href="#" class="a_btn04" onclick="CommonDialog.close('stampContainer');"></a> 
        <p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('View', 'window prompt'); ?></span></p>
        <h2 class="f26 "><?php echo Yii::t('Item', 'shorten the waiting time'); ?></h2>
        <div class="b_con_10 a_con_02 clearfix">				
            <div class="tx02">
                <?php $itemMeta35 = Yii::app()->objectLoader->load('ItemMeta', 35); ?>
                <em <?php
            if ($num1 == 0) {
                echo 'class="op05"';
            } else {
                echo 'onclick="CommonAP.useItem(1)"';
            }
                ?>>
                    <p><i class="a_img01"><img src="<?php echo $itemMeta35->getImagePath(); ?>" height="52" width="53" alt=""></i><span class="a_text01 color_jin">100%</span></p>
                    <span class="color_blue"><?php echo Yii::t('Item', 'all clear waiting time'); ?></span>
                    <?php if ($num1 == 0) { ?>
                        <i class="a_con_03 color_gray">x0</i>
                    <?php } else { ?>
                        <i class="a_con_03 color_brown">x<?php echo $num1 > 99 ? '99+' : $num1; ?></i>
                    <?php } ?>
                </em>
                <?php $itemMeta34 = Yii::app()->objectLoader->load('ItemMeta', 34); ?>
                <em <?php
            if ($num2 == 0) {
                echo 'class="op05"';
            } else {
                echo 'onclick="CommonAP.useItem(2)"';
            }
                ?>>
                    <p><i class="a_img01"><img src="<?php echo $itemMeta34->getImagePath(); ?>" height="52" width="53" alt=""></i><span class="a_text01 color_yin"><?php echo Yii::t('Item', 'reduce 4 hours'); ?></span></p>
                    <span class="color_blue"><?php echo Yii::t('Item', 'reduce 4 hours waiting time'); ?></span>
                    <?php if ($num2 == 0) { ?>
                        <i class="a_con_03 color_gray">x0</i>
                    <?php } else { ?>
                        <i class="a_con_03 color_brown">x<?php echo $num2 > 99 ? '99+' : $num2; ?></i>
                    <?php } ?>
                </em>
                <em onclick="CommonAP.buyAP(1)">
                    <p><i class="a_img01 a_tp10"><img src="themes/images/imga/ico18.png" alt=""><i class="a_ico20 a_up_yellow"></i></i><span class="a_text01 color_jin"><?php echo Yii::t('Item', 'all clear'); ?></span></p>
                    <span class="a_bg01"><?php echo $itemMeta35->getPrice(); ?></span>						
                </em>
                <em onclick="CommonAP.buyAP(2)">
                    <p><i class="a_img01 a_tp10"><img src="themes/images/imga/ico19.png" alt=""><i class="a_ico20 a_up_yellow"></i></i><span class="a_text01 color_yin"><?php echo Yii::t('Item', 'reduce 4 hours'); ?></span></p>
                    <span class="a_bg01"><?php echo $itemMeta34->getPrice(); ?></span>						
                </em>
            </div>
        </div>
    </div>
<?php } ?>
<script type="text/javascript">
    window.CommonAP = {
        UseUrl : '<?php echo $this->createUrl('Mail/StampUse'); ?>',
        BuyUrl : '<?php echo $this->createUrl('Mail/GoodsUse'); ?>',
		
        useItem : function(id) {
            if (id==1){
                var item='<?php echo Yii::t('Item', 'all clear'); ?>';
                var ID = 35;
            }else{
                var item='<?php echo Yii::t('Item', 'reduce 4 hours'); ?>';
                var ID = 34;
            }
            CommonDialog.confirm ("<?php echo Yii::t('Item', 'make sure to use'); ?>",function(){
                AjaxLoader.get(CommonAP.UseUrl+"&stampId="+ID+"&mailId="+window.stampShow.mailId,CommonAP.UseCB);
            });
        },

        UseCB : function(data){
            window.stampShow.playerMailInfo = data.playerMailInfo;
            window.stampShow.getDaysView = data.getDaysView;
            window.stampShow.over();
        },

        buyAP : function (id) {
            if (id==1){
                var item='<?php echo Yii::t('Item', 'all clear'); ?>';
                var itemID = 35;
                var ID = 80035;
            }else{
                var item='<?php echo Yii::t('Item', 'reduce 4 hours'); ?>';
                var itemID = 34;
                var ID = 80034;
            }
            CommonDialog.confirm ("<?php echo Yii::t('Item', 'make sure to buy'); ?>",function(){
                AjaxLoader.get(CommonAP.BuyUrl+"&goodsId="+ID+"&mailId="+window.stampShow.mailId+"&itemId="+itemID,CommonAP.ItemCB);
            });
        },

        ItemCB : function(data){
            window.stampShow.playerMailInfo = data.playerMailInfo;
            window.stampShow.getDaysView = data.getDaysView;
            window.stampShow.over();
        }
    }
</script>
