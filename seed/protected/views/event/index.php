<!--#include file="doctype.shtml"-->
<link rel="stylesheet" type="text/css" media="screen" href="themes/new_0001.css" />
<div style=" background:#a5cfdb;">
    <div id="wrapper" style="position: relative;overflow: hidden;height:640px;" >
        <div class="new_activity" style="padding-top:347px;">
            <a href="native://close" class="new_btn">戻る</a>
            <div class="banner" onclick="displayEvent('<?php echo $this->createUrl('apurifanCode/default/index')?>')">
				<img src="themes/subject/img_0001/img.jpg" style="margin-top:19px;" />
				<h4 class="banner_time">開始時間：2012年9月22日</h4>
			</div>
			<div class="banner" onclick="displayEvent('<?php echo $this->createUrl('famitsuCode/default/index')?>')">
				<img src="themes/subject/img_0001/img_01.jpg" style="margin-top:19px;" />
				<h4 class="banner_time">有効期限：2012年8月-10月31日</h4>
			</div>
        </div>
    </div>
</div>
<script type="text/javascript">
	var myScroll;
	function loaded() {
		myScroll = new iScroll("wrapper",{
			hScrollbar:false,
			vScrollbar:true,
			scrollbarClass: 'myScrollbar'
		});
	}
	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
    
    var displayEvent = function(url) {
        ajaxLoader.get(url,function(data){ CommonDialog.create('EventPage',data); });
    }
</script>


<!--#include file="footer.shtml"-->
