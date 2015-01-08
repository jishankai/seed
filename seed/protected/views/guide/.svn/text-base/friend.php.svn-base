<div id="guide" style="width:421px; height:337px; overflow:hidden;">
	<div class="b_text06" style="float:right; z-index:10">
    	<i class="text06pic"></i>
    	<div class="long">
    		<span id="guideDialogMessage"></span>
    		<em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
    	</div>
	</div>
</div>
<script language="javascript">
var FriendGuide = {
    step : 0 ,
    zIndex : 10 ,
    nextStep : function(){
        this.step ++ ;
        switch( this.step ) {
            case 1 :
                this.showMessage1();
                break ;
            case 2 :
            	NativeApi.callback({'accessLevel':'104','close': 'close'});
                break ;
            case 3 : 
            	this.showMessage2();
                break ;
            case 4 : 
            	this.showMessage3();
                break ;
            case 5 :
            	NativeApi.delay(true);
        		ajaxLoader.get('<?php echo $this->createUrl('guide/saveStatus',array('noState'=>1));?>',function(){
                    NativeApi.callback('accessLevel',106).doRequest();
                });
        		NativeApi.close();
                break ;
            //case 4 :
            //    this.showMessage4();
            //	break ;
            //default:
                console.log(this.step);
        }
    } ,
    showMessage1 : function() {
		var self = this ;
		$('#guideDialogMessage').html('<?php echo Yii::t('GuideMessage', 'message_106')?>');
		$('.long').click(function(){FriendGuide.nextStep()});
    },
    showMessage2 : function() {
		var self = this ;
		$('#guideDialogMessage').html('<?php echo Yii::t('GuideMessage', 'message_107')?>').unbind('click');
		SeedUnit( 'seed1',<?php echo SeedData::getAllDisplayData(1001,2031,$budId=0,$dressId=0);?>,0.2 );
		SeedUnit( 'seed2',<?php echo SeedData::getAllDisplayData(1001,2001,3001,$dressId=0);?>,0.2 );
		$('.long').click(function(){FriendGuide.nextStep()});
		//NativeApi.callback('accessLevel',105);
    },
    showMessage3 : function() {
		var self = this ;
		$('#guideDialogMessage').html('<?php echo Yii::t('GuideMessage', 'message_108')?>').unbind('click');
		$('.long').click(function(){FriendGuide.nextStep()});
		//NativeApi.callback('accessLevel',106);
    },

    showMessage4 : function() {
		var self = this ;
		ajaxLoader.get('<?php echo $this->createUrl('guide/saveStatus',array('noState'=>1));?>',function(){});
    }
}

var currentAccessLevel = <?php echo $currentAccessLevel;?>;
$(document).ready(function(){
    if( currentAccessLevel == 104 ) {
        FriendGuide.step = 2 ;
        FriendGuide.nextStep();
    }
    else {
        FriendGuide.nextStep();
    }
}) ;

</script>