
<div style="text-align:center">
<ul>
<li><?php echo "LV";?><span id='level'><?php echo $level ?></li>
<li><?php echo "EXP";?><span id='exp'><?php echo $exp=='MAX' && $exp=='MAX'? 'MAX': $exp.'/'.$expMax?></span>
</li>
<li><?php echo "行动力:";?><span id='actionPoint'><?php echo $actionPoint ?>/<?php echo $actionPointMax?></span>
</li>
<li><span id="actionPointTime"></span></li>
<li><?php echo "游戏币:";?><span id='gold'><?php echo $gold ?></li>
<li><?php echo "金币:";?><span id='userMoney'><?php echo $userMoney ?></li>
</ul>
</div>

<script type="text/javascript">
$(document).ready(function(){
    <?php if($actionPoint < $actionPointMax){?>
    SeedTimer.start('actionPointTime', <?php echo $actionPointTime?>, function(){ajaxLoader.get('<?php echo $this->getController()->createUrl('player/flushActionPoint')?>', actionPointCallBack);}, 'm:s');
    <?php } ?>
});

function actionPointCallBack(data){ 
	$('#actionPoint').html(data.actionPoint+'/'+data.actionPointMax);
	$('#actionPointTime').html('');
    if (data.actionPoint < data.actionPointMax)
    	SeedTimer.start('actionPointTime', data.actionPointTime, function(){ajaxLoader.get('<?php echo $this->getController()->createUrl('player/flushActionPoint')?>', actionPointCallBack);}, 'm:s');
}
</script>
