
<div class="frame b_frame">
    <a href="#" class="a_btn04" onclick="<?php echo $isAjax?'CommonDialog.close(\'SeedInfoContainer\');':'NativeApi.close();';?>"></a>
    <p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('Seed','detail title');?></span></p>
    <p class="tx001"><?php echo $seed->getName(); ?></p>
    
<div class="b_con_09 clearfix">
    <div class="tx01">
        <b><?php echo Yii::t('Seed','level name');?> <?php echo $seed->getMyLevel(); ?></b>
        <span id="seedImageArea" style="float: left; margin-bottom: 10px;margin-left: 30px;"></span>
    </div>
    <div class="tx02">
        <img src="themes/images/imgb/ico_10.png" alt="">
        <strong><span class="black" id="SeedCurrentGrowValue"><?php echo $seed->getGrowValue();?></span>/<?php echo $seed->getMaxGrowValue();?></strong>
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
        
    </div>
</div>

   
<div class="b_con_08 clearfix">
    <a href="" class="b_btn_02" id="breedButton"></a> 
    <p class="midle">
        <?php echo Yii::t('Seed','breed CD desc');?>
        <span class="b_time" id="breedCDTime">00:00:00</span>
    </p>
    <?php if( $isFriend||$seed->playerId==$this->playerId ) { ?>
    <span id="fosterBackButton">
    <a href="" class="b_btn_06"></a> 
    <p class="b_arrows">
        <?php echo Yii::t('Seed','foster back desc');?>
    </p>
    </span>
    <?php }?>
</div>

</div>
<div style="clear:both; width:100%;height:20px;"></div>


<script language="javascript">
var currentSeedId = <?php echo $seedId; ?>; 
var isMine = <?php echo $this->playerId==$seed->playerId?1:0;?>; 
var isGrown = <?php echo $seed->isGrown()?1:0; ?>; 
var breedCDTime = <?php echo $seed->getBreedCDTime(); ?>; 
var isFriend = <?php echo $isFriend?1:0; ?>; 

var growSpeed = <?php echo $seed->getGrowSpeed();?>;
var currentGrowValue = <?php echo $seed->getGrowValue();?>;
var maxGrowValue = <?php echo $seed->getMaxGrowValue();?>;


var refreshCurrentSeed = function(){
    if( <?php echo $isAjax?1:0;?> ) {
        SeedAction.showInfo(currentSeedId);
    }
    else {
        Common.refreshCurrentPage();
    }
}

$(document).ready(function(){
    //种子成长计时
    var growLimitSecond = <?php echo $seed->getGrowLimitSecond(); ?> ;
    if( growLimitSecond>0&&<?php echo $supplyPower; ?>>0 ) {
        $('#SeedGrowLimitTime').html( SeedTimer.getTimeStr(growLimitSecond) );
        SeedTimer.start('SeedGrowLimitTime',growLimitSecond,refreshCurrentSeed) ;
        
        if( window.growingSeed ) {
            window.growingSeed.clear();
        }
        window.growingSeed = new SeedGrowing(<?php echo json_encode(array_merge(array('url'=>$this->createUrl('seed/growData',array('seedId'=>$seedId,'growPeriod'=>$seed->growPeriod))),$seed->getGrowValueData()));?>);
    }
    

    <?php if( !empty($seed) ) echo 'SeedUnit("seedImageArea",'.$seed->getDisplayData().');'; ?>
    if( <?php echo !$isVisual&&($this->playerId==$seed->playerId||$this->playerId==$garden->playerId)?1:0;?> ) {
        $('#fosterBackButton').show().click(function(){
            CommonDialog.confirm ("<?php echo !$isVisual&&$this->playerId==$seed->playerId?Yii::t('Seed','get seed back'):Yii::t('Seed','send seed back');?>",function(){
                NativeApi.delay(true);
                AjaxLoader.get('<?php echo $this->createUrl('friend/recycleSeed')?>&friendId=<?php echo $gardenPlayerId; ?>&seedId=<?php echo $seedId; ?>',function(){
                    NativeApi.close().doRequest();
                });
            });
            return false ;
        });
    }
    else {
        $('#fosterBackButton').hide();
    }

    if( isGrown&&((<?php echo !$isVisual?1:0;?>&&isFriend)||isMine||<?php echo $isVisual&&$seed->canBreed()?1:0;?>) ) {
        $('p.b_arrows').hide();
        $('#breedButton').show().click(function(){
            if( <?php echo $breedLock;?> ) {
                CommonDialog.alert('<?php echo Yii::t('Seed','you can not breed with the seed');?>');
            }
            else if( breedCDTime>0&&!isMine ) {
                selectLittleGardenList.show('<?php echo $this->createUrl('seed/breedIndex',array('seedId'=>$seedId,'native'=>!empty($_REQUEST['native'])?1:0));?>','url');
            }
            else {
                selectLittleGardenList.show('<?php echo $this->createUrl('seed/breedIndex',array('seedId'=>$seedId,'native'=>!empty($_REQUEST['native'])?1:0));?>','url');
            }
            return false ;
        });
        if( breedCDTime>0&&!isMine ) {
            $('#breedButton').addClass('b_ico_heart').removeClass('b_btn_02');
            $('#breedCDTime').parent().show();
            SeedTimer.start('breedCDTime',breedCDTime,function(){
                breedCDTime = 0 ;
                $('#breedCDTime').parent().hide();
                $('#breedButton').addClass('b_btn_02').removeClass('b_ico_heart');
            });
        }
        else {
            $('#breedCDTime').parent().hide();
        }
    }
    else {
        $('#breedCDTime').parent().hide();
        $('#breedButton').hide();
    }

    var growLimitSecond = <?php echo $seed->getGrowLimitSecond(); ?> ;
    if( growLimitSecond>0 ) {
        $('#growLimitTime').html( SeedTimer.getTimeStr(growLimitSecond) );
    }
    
});


</script>
