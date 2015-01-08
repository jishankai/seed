<div class="b_con_01 b_bg_01 a_bg_01" style="height: 445px;">
		<div class="pr01"><span class="a_bar1"><?php echo Yii::t('Seed','breed title');?></span></div>
        <div class="b_icobg" onclick="Common.goUrl('<?php echo (!$isNative?$this->createUrl('garden/gardenList',array('playerId'=>$seed->playerId)):$this->createUrl('/seed/detail',array('seedId'=>$seedId,'native'=>1))); ?>');"><i class="b_ico"></i></div>
		<a href="#" class="a_btn04"></a>
		<div class="a_top">
			<div class="a_con_f01 a_frame_01">
				<p><i><strong id="defaultSeed" style="float:left;margin-top:15px;"></strong><span style="margin-top:150px;"><?php echo Yii::t('Seed','level name');?><?php echo $seed->getMyLevel();?></span></i>
                <i><strong id="breedCompareSeed" style="float:left;margin-top:15px;"></strong><span id="breedCompareLevel" style="margin-top:150px;"></span></i></p>
				<div class="a_abtn1 op05" onclick="breedAction()">
					<em><?php echo Yii::t('Seed','breed button');?></em>
					<img src="themes/images/imga/ico16.png" width="28" height="25" /><span class="color_red">-2</span><img src="themes/images/imgb/ico_7.png" width="28" height="25" /><span class="color_green" id="breedPrice">  </span>
				</div>
			</div>
	
			<div class="a_con_f02 clearfix fl">
				<h4 id="breedName">&nbsp;</h4>
				<ul id="breedSeedsArea">
<?php
for( $i=0;$i<9;$i++ ) echo '<li class="a_btn03 a_abtn03"><a href="#"></a></li>';
?>
					
				</ul>
			</div>
	
		</div>
	
			<div class="a_scroll pr a_ascroll">
				<div class="scroll_top"></div>
				<div class="a_list1 a_alist1" id="wrapper">
					<ul>
						<li>
							<div class="a_frame_01">
                            <?php 
                            $count = 0 ; 
                            $seedDatas=$breedDatas = array();
                            foreach($seedList as $s){
                                if( !$s->isGrown()||$s->seedId==$seedId ) continue ;
                                $count ++ ;
                                $seedDatas[$s->seedId] = $s->getDisplayData(false) ;
                                $breedDatas[$s->seedId] = SeedModel::getBreedData($seed,$s);
                            ?>
                                <i style="width:90px"><span style="width:90px"><?php echo Yii::t('Seed','level name');?><?php echo $s->getMyLevel();?></span><a href="#" id="seedArea<?php echo $s->seedId; ?>" onclick='setBreedMessage(<?php echo $s->seedId;?>)' style="float:left;margin-top:15px; margin-left:5px;"></a></i>
                            <?php
                            }
                            ?>
							</div>
						</li> 	
					</ul>
				</div>
			</div>
</div>


<input type="hidden" value="2" id="APtype" />

<script language="javascript">
var seedDatas = <?php echo json_encode($seedDatas);?> ;
var breedDatas = <?php echo json_encode($breedDatas);?> ;

var currentSeedId = <?php echo $seedId; ?>;
var selectSeedId = 0 ;
var isProcessing = false ;

var setBreedName = function(name) {
    if( !name ) name = '&nbsp;'; 
    $('#breedName').html(name);
}
var setBreedMessage = function(seedId) {
    var htmlData = '';
    var count = 0 ;
    var dataArea = $('#breedSeedsArea') ;
    setBreedName();
    if( !seedId ) {
        $('#breedCompareLevel').empty();
        $('#breedCompareSeed').empty();
        dataArea.empty();
        for( var i=count ;i<9;i++ ) {
            dataArea.append('<li class="a_btn03 a_abtn03"><a href="#"></a></li>');
        }
        if( selectSeedId ) {
            $('#seedArea'+selectSeedId).removeClass('op05').parent().removeClass('aop05').children('em').remove();
            selectSeedId = 0 ;
        }
        $('.a_abtn1').addClass('op05');
    }
    else {
        $('.a_abtn1').removeClass('op05');
        var breedData = breedDatas[seedId];
        if( breedData['isLock'] ) {
            CommonDialog.alertDisappear('<?php echo Yii::t('Seed','you can not breed with the seed');?>');
            return false;
        }
        dataArea.empty();
        if( selectSeedId ) {
            $('#seedArea'+selectSeedId).removeClass('op05').parent().removeClass('aop05').children('em').remove();
        }
        $('#seedArea'+seedId).addClass('op05').parent().addClass('aop05').append('<em></em>');
        selectSeedId = seedId ;
        dataArea.empty();
        $('#breedCompareSeed').empty();
        SeedUnit('breedCompareSeed',seedDatas[seedId],0.75);
        $('#breedCompareLevel').html('<?php echo Yii::t('Seed','level name');?>'+breedData['level']);
        //setBreedName(breedData['name']);
        for( i in breedData['seeds'] ) {
            dataArea.append('<li class="a_btn03 a_abtn03"><a href="#" onclick="setBreedName(\''+breedData['seeds'][i]['name']+'\');"><span style="float:left;margin-left:35px; margin-top:30px;" id="breedSeed'+count+'"></span></a></li>');
            SeedUnit('breedSeed'+count,breedData['seeds'][i]['displayData'],0.4);
            count ++ ; 
        }
        if( breedData['isChange'] ) {
            dataArea.append('<li class="a_btn03 a_abtn03"><a href="#"><img src="themes/images/imga/ico33.png"></a></li>');
            count ++ ;
        }
        for( var i=count ;i<9;i++ ) {
            dataArea.append('<li class="a_btn03 a_abtn03"><a href="#"></a></li>');
        }
        $('#breedPrice').html('-'+breedData['price']);
        //$('#breedSeedsArea').html(htmlData);
    }
}

var breedAction = function( isClear ){
    if( isProcessing ) return ;
    isProcessing = true ;
    setTimeout( function(){isProcessing = false},1000 ) ;
    if( !selectSeedId ) {
        return ;
    }
    else {
        if( !isClear ) isClear = 0 ; 
        ajaxLoader.get('<?php echo $this->createUrl('seed/breedApply',array('native'=>$isNative?1:0)); ?>&fromSeedId='+currentSeedId+'&partnerSeedId='+selectSeedId+'&clearCDTime='+isClear,function(data){ 
                CommonDialog.create('breedResultDialog',data);
                if( <?php echo $isCurrentGuide; ?> ) {
                    guideResult();
                }
            });
    }
}

$(document).ready(function(){
    for( id in seedDatas ) {
        SeedUnit('seedArea'+id,seedDatas[id]);
    }
    SeedUnit('defaultSeed',<?php echo $seed->getDisplayData();?>,0.75);
});
</script>



<?php if($isCurrentGuide){ ?>
<div style="width:421px; height:337px; overflow:hidden; position:absolute;bottom:0;right:0;z-index:2002;" id="messageContainer1">
    <div class="b_text06 b_text06_1" style="float:left" id="dialogContent">
        <i class="text06pic"></i>
        <div class="long">
            <span id="breedGuideMessage">
            </span>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
</div>

<div style="width:421px; height:337px; overflow:hidden; position:absolute;bottom:0;left:0;z-index:2002; display:none" id="messageContainer2">
    <div class="b_text06" style="float:right;">
    <i class="text06pic"></i>
        <div class="long">
            <span id="breedGuideMessage2">
            </span>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
        </div>
    </div>
</div>

<script>
LoadAction.push(function(){
    $('div.guide_main_cover').show().css('z-index',2000);
    var dataString = '<?php echo Yii::t('GuideMessage','message_81');?>';
    var tempArray = dataString.split('|');
    var messageCount = 0; 

    var nextFeedMessage = function(){
        $('#breedGuideMessage').html( tempArray[messageCount] ).parent().unbind('click').click(function(){
            nextFeedMessage();
        });
        messageCount ++ ;
        if( messageCount>=tempArray.length ) {
            //$('#breedGuideMessage').parent().unbind('click').click(function(){
                $(this).unbind('click');
                breedGuideStart();
                $('em.b_text05goon').hide();
            //});
        }
    }
    nextFeedMessage();
});
var breedGuideStart = function(){
    $('#page').append('<div class="b_text07 left"><em class="star"></em><em class="new_hand_04"><span class="new_hand1"></span><span class="new_hand2"></span></em><em class="fangxiang new_left" style="top:60px"></em></div>');
    $('em.star').css({'top':70})
    $('div.b_text07').css({'position':'absolute','z-index':2001,'left':70,'bottom':-95}).click(function(){
        for( id in seedDatas ) {
            setBreedMessage(id);
            break ;
        }
        $('#messageContainer1').hide();
        $('#messageContainer2').show();
        $('em.b_text05goon').show();
        $('#breedGuideMessage2').html( '<?php echo Yii::t('GuideMessage','message_81_1');?>' ).parent().unbind('click').click(function(){
            $('#messageContainer2').hide();
            $('#messageContainer1').show();
            $('#breedGuideMessage').html( '<?php echo Yii::t('GuideMessage','message_81_2');?>' ).parent().unbind('click');
            $('.new_hand_04').removeClass('new_hand_04').addClass('new_hand');
            $('.fangxiang.new_left').css({'top':-15,'right':-35});
            $('em.star').remove();
            for( i=-2;i<3;i++ ){
                $('div.b_text07').append('<em class="star" style="left:'+(i*50)+'px;"></em>');
            }
            $('div.b_text07').show().css({'left':170,'bottom':-10}).unbind('click').click(function(){
                breedAction();
            });
            $('em.b_text05goon').hide();
        });
        $('div.b_text07').hide();
    });
}
var guideResult = function(){
    $('#breedGuideMessage').parent().parent().parent().remove();
    //ajaxLoader.get( '<?php echo $this->createUrl('guide/saveStatus',array('accessLevel'=>85,'noState'=>1));?>',function(){
        $('em.fangxiang').removeClass('new_left').addClass('new_right').css('right',270);
        $('em.star').remove();
        $('div.b_text07').append('<em class="star" style="left:0px;"></em>');
        $('div.b_text07').css({'left':'auto','bottom':'auto','top':55,'right':-170}).unbind('click').click(function(){
            $(this).remove();
            guideOver();
        });
    //} );
}
var guideOver = function(){
    $('#page').empty();
    NativeApi.callback({'userguide':'0','accessLevel':'85'});
}
$(document).ready(function(){
    if( <?php echo GuideModel::getAccessLevel($this->playerId)==85?1:0?> ){
        guideOver();
    }

});
</script>
<?php } ?>

