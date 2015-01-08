<?php if ($type == 3) {?>
		<div class="frame b_frame02 a_frame01">
        <a href="#" class="a_btn04" onclick="CommonDialog.close('noticeContainer');");></a>'  
        <span class="a_bar1">ヒント</span> 
        <h2 class="f26 ">需要回復行動力がありますか？ </h2>    
			<div class="b_con_10 a_con_02 clearfix">				
				<div class="tx02">
					<em <?php if ($num1 == 0) {echo 'class="op05"';}else{echo 'onclick="CommonAP.useItem(1)"';}?>>
						<p><i class="a_img01"><img src="themes/img/pic_13.png" alt=""></i><span class="a_text01 color_jin">100%</span></p>
						<span class="color_blue">全回薬剤</span>
						<?php if ($num1 == 0) {?>
						<i class="a_con_03 color_gray">x0</i>
						<?php }else{?>
						<i class="a_con_03 color_brown">x<?php echo $num1?></i>
						<?php }?>
						
					</em>
					<em <?php if ($num2 == 0) {echo 'class="op05"';}else{echo 'onclick="CommonAP.useItem(2)"';}?>>
						<p><i class="a_img01"><img src="themes/img/pic_13.png" alt=""></i><span class="a_text01 color_yin">100%</span></p>
						<span class="color_blue">半回薬剤</span>
						<?php if ($num2 == 0) {?>
						<i class="a_con_03 color_gray">x0</i>
						<?php }else{?>
						<i class="a_con_03 color_brown">x<?php echo $num2?></i>
						<?php }?>
					</em>
					<em onclick="CommonAP.buyAP(1)">
						<p><i class="a_img01 a_tp10"><img src="themes/images/imga/ico16.png" alt=""><i class="a_ico20 a_up_yellow"></i></i><span class="a_text01 color_jin">全回</span></p>
						<span class="a_bg01"><?php echo $restoreAllGoods->getPrice();?></span>						
					</em>
					<em onclick="CommonAP.buyAP(2)">
						<p><i class="a_img01 a_tp10"><img src="themes/images/imga/ico17.png" alt=""><i class="a_ico20 a_up_yellow"></i></i><span class="a_text01 color_yin">半回</span></p>
						<span class="a_bg01"><?php echo $restoreHalfGoods->getPrice();?></span>						
					</em>
				</div>
			</div>			
			<a href="#" class="b_btn_08 a_btn_01" onclick="CommonDialog.close('noticeContainer');">もうしばらく</a>
        </div>
<?php }?>
<?php if ($type == 2) {?>	
		<div class="frame b_frame a_frame01">
        <a href="#" class="a_btn04" onclick="CommonDialog.close('noticeContainer');"></a>
        <span class="a_bar1">ヒント</span>   
        <p class="tx01 a_color_red">空間の不足、どうぞ倉庫を整理する。 </p>    
        <div class="a_button01" onclick="Common.goUrl('<?php echo $this->createUrl('items/itemShow')?>');"><span class="a_button01bg1"></span>倉庫に行って</div>
        </div>
        <script>setTimeout("CommonDialog.close('noticeContainer');", 5000 )</script>
<?php }?>	
<?php if ($type == 1) {?>	
		<div class="frame b_frame a_frame01">';
        <a href="#" class="a_btn04" onclick="CommonDialog.close('noticeContainer');"></a>
        <span class="a_bar1">ヒント</span>  
        <p class="tx01 a_color_red">空間の不足、どうぞ倉庫を整理する。 </p>    
        <div class="a_button01" onclick="Common.goUrl('<?php echo $this->createUrl('items/itemShow');?>');"><span class="a_button01bg1"></span>倉庫に行って</div>
        <a href="#" class="b_btn_07 a_btn_01" onclick="CommonDialog.close('noticeContainer');">继续探索</a>
        </div>
<?php }?>
<script type="text/javascript">
window.CommonAP = {
		UseUrl : '<?php echo $this->createUrl('items/use'); ?>',
		BuyUrl : '<?php echo $this->createUrl('shop/confirm'); ?>',
		
		useItem : function(id) {
			if (id==1){
				var item='全恢复';
				var ID = 38;
			}else{
				var item='半恢复';
				var ID = 37;
			}
			CommonDialog.confirm ("确定使用"+item,function(){
				AjaxLoader.get(CommonAP.UseUrl+"&itemId="+ID,CommonAP.UseCB);
			});
		},

		UseCB : function(data){
			if (data.isOK==true) {
				var mapId = document.getElementById("mapId").value;
				Common.goUrl("<?php echo $this->createUrl('map/index');?>"+"&mapId="+mapId);
			}
		},

		buyAP : function (id) {
			if (id==1){
				var item='全恢复';
				var ID = <?php echo $restoreAllGoods->goodsId;?>;
			}else{
				var item='半恢复';
				var ID = <?php echo $restoreHalfGoods->goodsId;?>;
			}
			CommonDialog.confirm ("确定购买"+item,function(){
				AjaxLoader.get(CommonAP.BuyUrl+"&goodsId="+ID,CommonAP.ItemCB);
			});
		},

		ItemCB : function(data){
			var mapId = document.getElementById("mapId").value;
			//Common.goUrl("<?php echo $this->createUrl('map/index');?>"+"&mapId="+mapId);
            Common.refreshCurrentPage();
		}
}
</script>
