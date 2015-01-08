<?php
$dataArray = isset($playerData[$budId])?$playerData[$budId]:array();
ksort($dataArray);
$maxCount = $catalogType==1?$catalog->getFaceMaxCount():$catalog->getDressMaxCount();
?>

<div class=" b_bg_04">
	<a href="<?php echo $this->createUrl('catalog/subMain',array('bodyId'=>$bodyId));?>" class="b_ico"></a>
    <a style="right:15px; top:15px;" href="#" onclick="NativeApi.close()" class="a_btn04"></a>
    <div class="p10 ac mb20"><span class="a_bar1"><?php echo Yii::t('Catalog','catalog title');?></span></div>
	<div class="b_text02">
        <?php if($catalogType==1) {?>
        <a style="left:100px;top:-60px;" class="b_btn_01"><img src="themes/images/imgb/pic_19.png" alt=""/></a>
        <a  style="left:200px;top:-67px;" class="b_btn_01_on" href="<?php echo $this->createUrl('/catalog/subIndex',array('bodyId'=>$bodyId,'budId'=>$budId,'type'=>2));?>"><img src="themes/images/imgb/pic_18.png" alt=""/></a>
        <?php  } else {?>
        <a style="left:100px;top:-70px;" class="b_btn_01_on" href="<?php echo $this->createUrl('/catalog/subIndex',array('bodyId'=>$bodyId,'budId'=>$budId,'type'=>1));?>"><img src="themes/images/imgb/pic_19.png" alt=""/></a>
        <a  style="left:200px;top:-57px;" class="b_btn_01"><img src="themes/images/imgb/pic_18.png" alt=""/></a>
        <?php } ?>
    </div>

	<div class="b_con_02" style="overflow:hidden;">
		 <div class="b_text03" style="padding-left:24px;">
            <p style="position:relative;">
                <span id="currentSeed" style="position:absolute; left:-60px; top:24px;"></span>
                <i></i>
                <span><?php echo SeedData::getAllName( $catalogData->bodyId,0,$budId );?></span>
                <b> <?php echo floor(count($dataArray)/$maxCount*10000)/100; ?>%</b>
                <samp class="this_tx"><?php echo count($dataArray).' / '.$maxCount; ?> </samp>
            </p>
        </div>
        <div style="overflow:hidden;" id="wrapper">
        <div class="b_list_04">
<?php 
foreach( $dataArray as $id=>$data ) {
    if( $catalogType==1 ) {
        $faceId = $id ;
        $dressId = 0 ;
    }
    else {
        $faceId = 0 ;
        $dressId = $id ;
    }
?> 
            <li>
                <span class="img01"><div id="seedArea<?php echo $bodyId.$budId.$faceId.$dressId;?>" style="margin-left:35px;margin-top:35px;"></div></span>
                <div class="fl">
                    <?php if($catalogType==1) { ?>
                    <b><?php echo SeedData::getAllName( $catalogData->bodyId,$faceId,$budId,$dressId ); ?></b>
                    <span class="pl20"><?php echo Yii::t('Seed','level name');?><?php echo Catalog::getSeedLevel( $catalogData->bodyId,$budId,$faceId,$dressId ); ?></span>
                     <samp class="this_tx02 "><img src="themes/images/imgb/ico_7.png" alt=""><?php echo $catalog->getPrice($catalogData->bodyId,$faceId,$budId); ?> </samp>
    				<a href="javascript:void(0)" onclick="BuySeed(<?php echo $faceId.','.$catalog->getPrice($catalogData->bodyId,$faceId,$budId); ?>)" class="a_btn03"><img src="themes/images/imgb/pic_16.png"></a>
                    <?php } else { ?>
                    <b class="mt20"><?php echo SeedData::getAllName( $catalogData->bodyId,$faceId,$budId,$dressId ); ?></b>
                    <?php } ?>
                </div>
                <script language="javascript">$(document).ready(function(){SeedUnit('seedArea<?php echo $bodyId.$budId.$faceId.$dressId;?>',<?php echo SeedData::getAllDisplayData($catalogData->bodyId,$budId,$faceId,$dressId);?>,0.5);});</script>
            </li>
<?php } ?></div>
        </div>
    	
    </div>
</div>	



<script language="javascript">
var BuySeed = function( faceId,price ) {
    selectLittleGardenList.show(function(){ 
        ajaxLoader.get( '<?php echo $this->createUrl("/catalog/buy",array("bodyId"=>$catalogData->bodyId,"budId"=>$budId))?>&faceId='+faceId+'&gardenId='+selectLittleGardenList.selectId,function(data){
            CommonDialog.alert(data,selectLittleGardenList.close);
        } );
    });
} 

var CatalogScroll = function(){
    myScroll = new iScroll("wrapper",{hScrollbar:false, vScrollbar:false});
};


$(document).ready(function(){
    SeedUnit('currentSeed',<?php echo SeedData::getAllDisplayData($catalogData->bodyId,0,$budId);?>,0.35);
});


$(document).ready( function(){
    $('a.b_ico').click( function(){
        ajaxLoader.get( $(this).attr('href'),function(data){
            $('#page').html(data);
        } );
        return false;
    } );
    $('a.b_btn_01_on').click( function(){
        ajaxLoader.get( $(this).attr('href'),function(data){
            $('#page').html(data);
        } );
        return false;
    } );
} );
<?php if( $this->actionType==REQUEST_TYPE_AJAX) { ?>
CatalogScroll();
<?php } else { ?>
LoadAction.push(function(){
    CatalogScroll();
});
<?php } ?>
</script>
