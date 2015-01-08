<div>
<div style="width:400px; height:200px; border:solid 1px; margin:10px auto;">
<div>
<a href="<?php echo $this->createUrl('power/charge'); ?>">充能</a>
<span id='percent'><?php echo $supplyPowerPercent; ?></span>
%
<span id='supplyPowerTime'></span>
<span id='supplyPowerChangeTime' style='overflow: hidden;'></span>
<span id='supplyPower'><?php echo $supplyPower?></span>
/
<span id='supplyPowerMax'><?php echo $supplyPowerMax?></span>
</div>
</div>
</div>

<script type="text/javascript">
var minute = 0 ;
$(document).ready(function(){
    <?php if($supplyPower<=$supplyPowerMax and $supplyPower>0){?>
    SeedTimer.start('supplyPowerTime', <?php echo $supplyPowerTime?>, function(){});
    setInterval("supplyPowerChange()", 1000);
    <?php } ?>
});

function supplyPowerChange(){
	var newMinute = Math.ceil(SeedTimer.currentTime['supplyPowerTime']/60);
	if(minute > newMinute ){
		$('#supplyPower').html(parseInt($('#supplyPower').text())-1);
		$('#percent').html(Math.floor(parseInt($('#supplyPower').text())*100/parseInt($('#supplyPowerMax').text())));
	}
	minute = newMinute ;
}
</script>
