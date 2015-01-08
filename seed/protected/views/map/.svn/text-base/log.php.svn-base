<div class="b_con_01 b_bg_01" style="top: 8px;">
    <a href="#" class="a_btn04" onclick="CommonDialog.close()"></a>
	<h4 class="a_h401 color_blue"><?php echo Yii::t('Map', 'map_02')?></h4>
	<?php 
	if (empty($explore)){?>
		<div class="a_frame08">
			<img src="themes/images/imgb/img_02.png" alt="">
			<div class="quan1"><?php echo Yii::t('Map', 'map_05')?></div>
		</div>
	<?php }else {?>
	<ul class="a_list3" style="overflow: hidden;">
	<div id="scrollElement">
	<?php 
	for ($i = (count($explore)-1);$i>=0;$i--) {
		$message = $explore[$i];
	?>
		<li>
			<div class="a_list3_d1">
				<span><?php echo Yii::t('Map', 'map_03')?></span>
				<i>-<?php echo $message['actionPoint']?></i>
			</div>
			<div class="a_list3_d2">
				<span><?php echo Yii::t('Map', 'map_04')?></span>
				<?php
				switch ($message['type']){
					case "gold":
						echo '<em class="a_icocao">+'.$message['id'].'</em>';
						break;
					case "res":
					case "deco":
						echo '<div class="a_btn15"><img src="'.$message['data'].'" style="width:40px; height:40px; margin-left:5px;" /></div>';
						break;
					case "seed":
                        $seed = Yii::app()->objectLoader->load('Seed',$message['id']);
						echo '<div class="a_btn15"><div id="seedLog'.$message['id'].'" style="float:left;margin-top:30px;margin-left:30px;"></div></div><script>SeedUnit("seedLog'.$message['id'].'",'.$seed->getDisplayData().',0.3);</script>';
						break;
					default:
						break;
				}
				?>
				<em class="a_icoexp">+<?php echo $message['Exp']?></em>
				<i><?php 
					$time1 = time();
					$time2 = $message['time'];
					echo Yii::t('View','minute ago',array('{minute}'=>floor(($time1-$time2)/60)));
				?></i>
			</div>
		</li>
	<?php } ?>
	</div>
	</ul>
	<?php }?>
</div>
<script type="text/javascript">
$(function() {
	(function positionXY() {
        if( $('#scrollElement').attr('id') ) {
            Flipsnap('#scrollElement', {
                position: 'y' ,
            });
        }
	})();
});
</script>