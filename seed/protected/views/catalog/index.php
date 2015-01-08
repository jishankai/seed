<?php
    $currentCount = $catalog->getCountSum() ;
    $maxCount = $catalog->getMaxCount() ;
?>
<div class=" b_bg_04">
	<a href="#" onclick="NativeApi.close()" class="b_ico"></a>
    
    <div class="p10 ac mb20"><span class="a_bar1"><?php echo Yii::t('Catalog','catalog title');?></span></div>
	<div class="b_text02"> </div>
	
    <div class="b_con_02">
		 <div class="b_text03">
            <p>
                <span><?php echo Yii::t('Catalog','catalog percent');?></span>
                <b> <?php echo floor($currentCount/$maxCount*10000)/100; ?>%</b>
                <samp class="this_tx"><?php echo $currentCount.'/'.$maxCount; ?></samp>
            </p>
        </div>
        <div id="wrapper" style="overflow:hidden;">
        <div class="b_list_03">
        <?php foreach( $bodyList as $bodyId=>$data ) {
            $count = $catalog->getCountSum($bodyId) ;
            if( $count<=0 ) continue ;
            $max = $catalog->getMaxCount($bodyId);
            ?> 
            <a class="activate" href="<?php echo $this->createUrl('catalog/subMain',array('bodyId'=>$bodyId))?>">
                <span class="img01"><div id="seedArea<?php echo $bodyId;?>" style="margin-left:20px;"></div></span>
                <div class="fl">
                    <b><?php echo $data->getName(); ?></b>
                    <?php echo floor($count/$max*10000)/100; ?>%<samp class="this_tx" style="font-size:22px;"><?php echo $count.' / '.$max; ?></samp>
                </div>
                <script language="javascript">$(document).ready(function(){SeedUnit('seedArea<?php echo $bodyId;?>',<?php echo SeedData::getAllDisplayData($bodyId);?>);});</script>
            </a>
        <?php } ?>
        </div>
        </div>
    </div>
</div>

<script language="javascript">
var myScroll ;
var CatalogScroll = function(){
    myScroll = new iScroll("wrapper",{hScrollbar:false, vScrollbar:false});
}

$(document).ready( function(){
    $('a.activate').click( function(){
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
