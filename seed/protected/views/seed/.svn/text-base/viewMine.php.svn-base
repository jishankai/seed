<div class="frame b_frame">
    <a href="#" onclick="<?php echo $isAjax?'CommonDialog.close(\'SeedInfoContainer\');':'NativeApi.close();';?>" class="a_btn04"></a>
    <p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('Seed','detail title');?></span></p>
    <p class="tx001"><?php echo $seed->getName(); ?></p>
       
<div class="b_con_09 clearfix">
    <i id="favIcon" class="<?php echo $seed->favouriteFlag==1?'a_ico5':''; ?>" style="bottom:0px;"></i>
    <div class="tx01">
        <b><?php echo Yii::t('Seed','level name');?><?php echo $seed->getMyLevel(); ?></b>
        <span id="SeedImageArea" style="float:left;margin-left:32px; margin-bottom:10px;"></span>
    </div>
    <div class="tx02">
            <img src="themes/images/imgb/ico_10.png" alt="">
            <strong><span class="black" id="SeedCurrentGrowValue"><?php echo $seed->getGrowValue();?></span>/<i id="SeedMaxGrowValue"><?php echo $seed->getMaxGrowValue();?></i></strong>
            <span class="time"><span id="SeedGrowLimitTime"></span></span>
        <ul class="b_list_07" style="width:195px;">
            <?php 
            for( $i=4;$i>0;$i-- ){
                foreach( $seed->getAttributeLevels() as $k=>$v ) {
                    echo '<li class="'.($v>=$i?'':'op04').'"><img src="themes/images/imgb/ico_'.$k.'.png"></li>'; 
                }
            } ?>
        </ul>
        <p><img src="themes/images/imgb/ico_7.png" alt=""><span id="SeedCurrentPrice"><?php echo $seed->getPrice() ;?></span></p>
    </div>
    <div class="tx03">
        <?php echo $seed->favouriteFlag==1?'<a href="#" class="a_btn03 bk"></a>':'<a href="#" class="a_btn03" id="favouriteButton"><img src="themes/images/imga/ico11.png"></a>'; ?>
        <a href="#" class="a_btn03" id="sellButton"><img src="themes/images/imga/ico3.png"></a>
    </div>
</div>

    <?php if( !empty($_REQUEST['fosterSelect']) ) { ?>

    <div class="b_con_08 clearfix" style="position:static;">
        <a href="" class="b_btn_06"></a> 
        <p class="b_arrows">
            <?php echo Yii::t('Seed','foster back desc');?>
        </p>
    </div>

    <?php } else  { ?>

    <div class="b_con_08 clearfix">
        <?php echo $seed->growPeriod>=SEED_GROW_PERIOD_GROWN?'<a href="#" class="b_btn_02" onclick="breedSeed()"></a>':''; ?>
        <?php echo $seed->feedCount>0?'<a href="#" class="b_btn_03" onclick="feedSeed()"></a> ':''; ?>
        
        <?php echo $seed->growPeriod>=SEED_GROW_PERIOD_GROWING&&$gardenCount>1?'<a href="#" class="b_btn_04" onclick="moveCurrentSeed()"></a> ':''; ?>
        
        <?php echo $seed->growPeriod>=SEED_GROW_PERIOD_GROWING?'<a href="#" class="b_btn_05" onclick="startGame()"></a> ':''; ?>
        
    </div>

    <?php } ?> 
</div>
<div style="clear:both; width:100%;height:20px;"></div>




<script language="javascript">
var currentSeedId = <?php echo $seedId; ?>;
var selectSeedId = 0 ;
var breedMessage = '' ;
var growSpeed = <?php echo $seed->getGrowSpeed();?>;
var currentGrowValue = <?php echo $seed->getGrowValue();?>;
var maxGrowValue = <?php echo $seed->getMaxGrowValue();?>;
$(document).ready(function(){
    SeedTimer.clear2('SeedGrowLimitTime');
    <?php if( !empty($seed) ) echo 'SeedUnit("SeedImageArea",'.$seed->getDisplayData().');'; ?>

    var growLimitSecond = <?php echo $seed->getGrowLimitSecond(); ?> ;
    var supplyPowerSecond = <?php echo $supplyPower; ?>;
    if( growLimitSecond>0&&supplyPowerSecond>0 ) {
        $('#SeedGrowLimitTime').html( SeedTimer.getTimeStr(growLimitSecond) );
        SeedTimer.start('SeedGrowLimitTime',growLimitSecond,function(){}) ;
        if( supplyPowerSecond<growLimitSecond ){
            setTimeout(function(){
                SeedTimer.stop('SeedGrowLimitTime');
                if( window.growingSeed ) {
                    window.growingSeed.clear();
                }
                refreshCurrentSeed();
            },supplyPowerSecond*1000);
        }
        if( window.growingSeed ) {
            window.growingSeed.clear();
        }
        window.growingSeed = new SeedGrowing(<?php echo json_encode(array_merge(array('url'=>$this->createUrl('seed/growData',array('seedId'=>$seedId,'growPeriod'=>$seed->growPeriod))),$seed->getGrowValueData()));?>,refreshCurrentSeed);
    }
    

    $('#sellButton').click(function(){
        if( window.isSeedSelling ) return false ;
        window.isSeedSelling = true ;
        //NativeApi.delay(true);

        ajaxLoader.get('<?php echo $this->createUrl('seed/sell',array('seedId'=>$seedId)); ?>',function(data){
            CommonDialog.create('SellNoticeDialog','<div class="a_frame03">        <center><p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('Seed','sell get');?></span></p></center>                            <div class="tx02"> <i><span class="yezi">'+data.price+'</span></i> <i><span class="exp" style="height:42px;">'+data.exp+'</span></i> </div><div class="b_btnlist02"><a href="#" onclick="closeSellNotice()" class="b_btn_07 a_btn_02">OK</a>        </div>    </div>');
            window.closeSellNotice = function(){
                if( <?php echo !$isAjax?1:0;?> ) {
                    NativeApi.close();
                }
                CommonDialog.close('SellNoticeDialog');
            };
            if( <?php echo $isAjax?1:0;?> ) {
                CommonDialog.close('SeedInfoContainer');
                delSeedCallback(currentSeedId,data.price);
            }
            //NativeApi.doRequest();

            window.isSeedSelling = null ;
        });

        setTimeout(function(){window.isSeedSelling = false ;},3000);
    });
    $('#favouriteButton').click( function(){setFavourite(currentSeedId)});

    var refreshTime = <?php echo !empty($seed)?$seed->getGrowRefreshSeconds():-1; ?>;
    

});

var moveCurrentSeed = function(){
    <?php if($isAjax) { ?>
    shakeSeedId = currentSeedId;
    moveSeed();
    CommonDialog.close();
    <?php } else {?>
    Common.goUrl('<?php echo $this->createUrl('garden/gardenList',array('shakeSeedId'=>$seedId));?>');
    <?php } ?>
}

var breedSeed = function(){
    Common.goUrl('<?php echo $this->createUrl('seed/breedIndex',array('seedId'=>$seedId,'native'=>!empty($_REQUEST['native'])?1:0));?>');
    
}

var feedSeed = function(){
    Common.goUrl('<?php echo $this->createUrl('seed/feedIndex',array('seedId'=>$seedId,'native'=>!empty($_REQUEST['native'])?1:0));?>');
}

var alertAndRefresh = function(data){
    if( !Common.empty(data) )
        CommonDialog.alert(data,'Common.refreshCurrentPage()'); 
    else 
        Common.refreshCurrentPage() ;
}

var setFavourite = function(seedId) {
    CommonDialog.confirm( '<?php echo Yii::t('Seed','set favourite seed');?>', function(){
        ajaxLoader.get('<?php echo $this->createUrl('seed/favourite'); ?>&seedId='+seedId,function(){
            $('#favouriteButton').empty().addClass('bk');
            $('#favIcon').addClass('a_ico5');
            if( <?php echo $isAjax?1:0;?> ) {
                setSeedLoveCallback(currentSeedId);
            }
        });
    }) ;
}

var startGame = function(){
    NativeApi.callback({'startgame':'<?php echo $seedId;?>','close':'close'});
    return false ;
}

var refreshCurrentSeed = function(){
    if( <?php echo $isAjax?1:0;?> ) {
        SeedAction.showInfo(currentSeedId);
    }
    else {
        Common.refreshCurrentPage();
    }
}
</script>
 

<?php if($isCurrentGuide){ ?>
<script>
LoadAction.push(function(){
    $('div.guide_main_cover').show();
    $('a.b_btn_02').append('<div class="b_text07 left"><em class="star"></em><em class="new_hand" style="margin-left:-125px;"><span class="new_hand1"></span><span class="new_hand2"></span></em><em class="fangxiang new_left"></em></div>').css({'position':'relative','z-index':200});
});
</script>
<?php } ?>
