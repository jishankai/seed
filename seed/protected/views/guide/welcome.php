
<div class="frame_02 b_frame02 a_frame01">
    <div class="b_text04 b_text04_1"><?php echo Yii::t('GuideMessage','welcome title');?></div>
    <h2 class="f26 pt10 "></h2>
    <div class="frame_xsyd">
        <span id="welcomeMessage"></span>
    </div>
    <div class="b_btnlist01"><a href="#" onclick="WelcomeGuide.nextStep();" class="b_btn_07 a_btn_01"><?php echo Yii::t('GuideMessage','welcome button');?></a></div>
</div>
<div style="clear:both; width:100%;height:20px;"></div>


<script language="javascript">
var WelcomeGuide = {
    step : 0 ,
    zIndex : 10 ,
    isLock : false ,
    nextStep : function(){
        if( this.isLock ) return ;
        this.step ++ ;
        switch( this.step ) {
            case 1 :
                this.showMessage();
                break ;
            default: 
                this.over();
                //console.log(this.step);
        }
    } ,
    showMessage : function(){
        var self = this ;
        var data = '<?php echo Yii::t('GuideMessage','message_11'); ?>';
        var contents = data.split('|');
        var count = 0 ;
        $('#welcomeMessage').empty().parent().unbind('click');

        self.isLock = true ;
        var timer ;
        var setNextMessage = function(){
            if( !contents[count] ) {
                clearInterval( timer );
                $('#welcomeMessage').parent().unbind('click').click(function(){
                    self.over();
                    $(this).unbind('click');
                });
                self.isLock = false ;
                return ;
            }
            //$('#welcomeMessage').parent().unbind('click').click(function(){setNextMessage();});
            var elem = document.createElement('div');
            var configData = {
                1 : {'color':'blue'} ,
                2 : {'color':'red'} ,
                3 : {'color':'green'} ,
                4 : {'color':'red'} ,
                5 : {'color':'black'} ,
            }
            $(elem).hide().html( contents[count] ).fadeIn();
            if( configData[count] ) {
                $(elem).css('color',configData[count]['color']) ;
                //$(configData[count]['expr']).css('z-index',self.zIndex++);
                NativeApi.callback('accessLevel',count+10);
            }
            $('#welcomeMessage').append(elem);
            count ++ ;
        }
        setNextMessage();
        setInterval( function(){setNextMessage();},2000 );
    } ,
    over : function(){
        NativeApi.delay(true);
        ajaxLoader.get('<?php echo $this->createUrl('guide/saveStatus&accessLevel=19');?>',function(){
            $('#page').empty();
            NativeApi.close().doRequest();
        });
            
    },
    appendHand : function(){
        //$('#welcomeMessage').append('<div style="width:100%;clear:both;"><i class="new_hand" style="margin-left:200px;"></i></div>');
    }

}

var currentAccessLevel = <?php echo $currentAccessLevel;?>;
$(document).ready(function(){
    $('.b_frame05').hide();
    if( currentAccessLevel<=10 ){
        $('#welcomeMessage').fadeIn().html('<?php echo addslashes(Yii::t('GuideMessage','message_10')); ?>');
        $('#welcomeMessage').parent().click(function(){WelcomeGuide.nextStep()});
    }
    else {
        CommonDialog.alert('Error!');
    }
}) ;

</script>

