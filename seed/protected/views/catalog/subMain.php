<div class=" b_bg_04">
	<a href="<?php echo $this->createUrl('catalog/index');?>" class="b_ico"></a>
    <a style="right:15px; top:15px;" href="#" onclick="NativeApi.close()" class="a_btn04"></a>
    <div class="p10 ac mb20"><span class="a_bar1"><?php echo Yii::t('Catalog','catalog title');?></span></div>
	<div class="b_text02"> </div>
	
    <div class="b_con_02">
		 <div class="b_text03">
            <p style="postion:relative;">
                <span id="currentSeed" style="position:absolute; left:-70px; top:10px;"></span>
                <i></i>
                <span><?php echo SeedData::getAllName( $bodyId );?></span>
                <b><?php echo floor($count/$max*10000)/100; ?>%</b>
                <samp class="this_tx"><?php echo $count.' / '.$max; ?></samp>
            </p>
        </div>
        <div style="overflow:hidden;" id="wrapper">
        <div class="b_list_03">
        <?php foreach( $catalog->getBodyData($bodyId) as $budId=>$r ) {
                $current = $catalog->getCountSum( $bodyId,$budId ) ;
                $currentMax = $catalog->getMaxCount( $bodyId,$budId );
            ?> 
            <a class="activate" href="<?php echo $this->createUrl('catalog/subIndex',array('bodyId'=>$bodyId,'budId'=>$budId))?>">
                <span class="img01"><div id="seedArea<?php echo $bodyId.$budId;?>" style="margin-left:35px;margin-top:35px;"></div></span>
                <div class="fl">
                    <b><?php echo SeedData::getAllName($bodyId,0,$budId); ?></b>
                    <?php echo floor($current/$currentMax*10000)/100; ?>%<samp class="this_tx"><?php echo $current.' / '.$currentMax; ?></samp>
                </div>
                <script language="javascript">$(document).ready(function(){SeedUnit('seedArea<?php echo $bodyId.$budId;?>',<?php echo SeedData::getAllDisplayData($bodyId,0,$budId);?>,0.5);});</script>
            </a>
        <?php } ?>
        </div>
        </div>
    </div>
</div>	
<script language="javascript">
var CatalogScroll = function(){
    myScroll = new iScroll("wrapper",{hScrollbar:false, vScrollbar:false});
};

$(document).ready(function(){SeedUnit('currentSeed',<?php echo SeedData::getAllDisplayData($bodyId);?>,0.4);});

$(document).ready( function(){
    $('a.b_ico').click( function(){
        ajaxLoader.get( $(this).attr('href'),function(data){
            $('#page').html(data);
        } );
        return false;
    } );
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

