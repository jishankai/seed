<div class="frame b_frame02 a_frame01">
<a href="#" class="a_btn04" onclick="CommonAP.closeDialog();");></a>'  
<p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('View', 'window prompt'); ?></span></p>
<h2 class="f26 "><?php echo Yii::t('View', 'Demand recovery operation ability?'); ?></h2>    
    <div class="b_con_10 a_con_02 clearfix">				
        <div class="tx02">
            <em <?php if ($num1 == 0) {echo 'class="op05"';}else{echo 'onclick="CommonAP.useItem(1)"';}?>>
                <p><i class="a_img01" style="padding-top: 10px;"><img width="50" src="<?php echo $restoreAllItem->getImagePath();?>" alt=""></i><span class="a_text01 color_jin">100%</span></p>
                <span class="color_blue"><?php echo Yii::t('View', 'the full actionPoint back drug'); ?></span>
                <?php if ($num1 == 0) {?>
                <i class="a_con_03 color_gray">x0</i>
                <?php }else{?>
                <i class="a_con_03 color_brown">x<?php echo $num1?></i>
                <?php }?>
                
            </em>
            <em <?php if ($num2 == 0) {echo 'class="op05"';}else{echo 'onclick="CommonAP.useItem(2)"';}?>>
                <p><i class="a_img01" style="padding-top: 10px;"><img width="50" src="<?php echo $restoreHalfItem->getImagePath();?>" alt=""></i><span class="a_text01 color_yin">50%</span></p>
                <span class="color_blue"><?php echo Yii::t('View', 'the half actionPoint back drug'); ?></span>
                <?php if ($num2 == 0) {?>
                <i class="a_con_03 color_gray">x0</i>
                <?php }else{?>
                <i class="a_con_03 color_brown">x<?php echo $num2?></i>
                <?php }?>
            </em>
            <em onclick="CommonAP.buyAP(1)">
                <p><i class="a_img01 a_tp10"><img src="themes/images/imga/ico16.png" alt=""><i class="a_ico20 a_up_yellow"></i></i><span class="a_text01 color_jin"><?php echo Yii::t('View', 'full back'); ?></span></p>
                <span class="a_bg01"><?php echo $restoreAllGoods->getPrice();?></span>						
            </em>
            <em onclick="CommonAP.buyAP(2)">
                <p><i class="a_img01 a_tp10"><img src="themes/images/imga/ico17.png" alt=""><i class="a_ico20 a_up_yellow"></i></i><span class="a_text01 color_yin"><?php echo Yii::t('View', 'half back'); ?></span></p>
                <span class="a_bg01"><?php echo $restoreHalfGoods->getPrice();?></span>						
            </em>
        </div>
    </div>
</div>

<script type="text/javascript">
window.CommonAP = {
    UseUrl : '<?php echo $this->createUrl('items/use'); ?>',
    BuyUrl : '<?php echo $this->createUrl('shop/confirm'); ?>',
    ApUrl : '<?php echo $this->createUrl('map/getAP'); ?>',
    
    useItem : function(id) {
        if (id==1){
            var ID = <?php echo $restoreAllItem->id;?>;
        }else{
            var ID = <?php echo $restoreHalfItem->id;?>;
        }
        CommonDialog.confirm ('<?php echo Yii::t('Message','use item');?>',function(){
            AjaxLoader.get(CommonAP.UseUrl+"&itemId="+ID,CommonAP.APCB);
        });
    },

    UseCB : function(data){
        Common.refreshCurrentPage();
    },

    buyAP : function (id) {
        if (id==1){
            var ID = <?php echo $restoreAllGoods->goodsId;?>;
        }else{
            var ID = <?php echo $restoreHalfGoods->goodsId;?>;
        }
        CommonDialog.confirm ("<?php echo Yii::t('Message','buy item');?>",function(){
            AjaxLoader.get(CommonAP.BuyUrl+"&goodsId="+ID,CommonAP.APCB);
        });
    },

    ItemCB : function(data){
        Common.refreshCurrentPage();
    },

	APCB : function (data) {
		if (document.getElementById("APtype")) {
			var type = document.getElementById("APtype").value;
		} else {
			var type = null; 
		}
		switch (type) {
			case '1' :
				AjaxLoader.get(CommonAP.ApUrl,CommonAP.APCB2);
				CommonAP.closeDialog();
				break;
			case '2' :
				CommonAP.closeDialog();
				break;
			default :
				Common.refreshCurrentPage();
				break;
		}
   	},

   	APCB2 : function (data) {
   		var info=data.playerinfo;
   		document.getElementById("HEART2").innerHTML=info['AP']+'/'+info['APMAX'];
   		document.getElementById("HEART").style.width=info['AP2']+'%';
   		var HEART=document.getElementById("HEART2").innerText;
    	var AP=(HEART.split("/"))[0];
    	var APMAX=(HEART.split("/"))[1];
    	AP=parseInt(AP);
    	APMAX=parseInt(APMAX);
    	if (AP>=APMAX) {
    		$("#time").hide();
    		SeedTimer.clear2('time');
    		document.getElementById("time").innerHTML="";
        }  	
   	},
    
    closeDialog : function(){
        CommonDialog.close('EXCEPTION_TYPE_AP_NOT_ENOUGH');
    }
}
</script>