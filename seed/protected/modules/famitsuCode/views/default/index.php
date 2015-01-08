<div style="background-color:#a5cfdb">
<div id="wrapper_event" style="position: relative;overflow: hidden;height: 640px;">
<div class="new_activity"  style="background:none;padding-top:0;">
<?php
if (isset($playerId)) {
?>
<a href="native://action=registersuccess&playerId=<?php echo $playerId?>" class="new_btn">Start</a>
<?php 
} else {
?>
    <a href="#" onclick="CommonDialog.close('EventPage');" style="position:absolute;top:0;left:16px;" class="b_ico"></a>
<?php 
}
?>
	<img src="themes/subject/img_0001/text.png" alt="" style="margin-top:14px;"/>
    <div class="new_input">
    <input type="text" class="code" maxLength="16" placeholder="<?php echo Yii::t('FamitsuCodeModule.View', 'Input code')?>"/>
        <a href="#" class="b_btn_07 a_btn_01" id="submit"><?php echo Yii::t('FamitsuCodeModule.View', 'Apply')?></a>
	</div>
    <div class="new_img">
    	<img src="themes/subject/img_0001/img.png" alt=""/>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
	var myScroll;
	
		myScroll = new iScroll("wrapper_event",{
			hScrollbar:false,
			vScrollbar:true,
			scrollbarClass: 'myScrollbar'
		});
	
	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

    var isSubmitted = false;

    $(document).ready(function(){
    jQuery('#submit').bind('click', function(){
        var code = $('.code').val();
        if (!isSubmitted) {
            isSubmitted = true;
            ajaxLoader.get("<?php echo $this->createUrl('default/index') ?>&code="+encodeURIComponent(code),function(data){
                CommonDialog.alert('<?php echo Yii::t('FamitsuCodeModule.View', 'Success'); ?>');    
            });
            setTimeout("isSubmitted = false", 2000);
        }
        
    });
    });
</script>
<!--#include file="footer.shtml"-->
