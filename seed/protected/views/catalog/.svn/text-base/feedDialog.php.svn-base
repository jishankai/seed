<div class=" b_bg_04">
    <div class="p10 ac"><span class="a_bar1"><?php echo Yii::t('Catalog','breed dialog title');?></span></div>
	<a href="#" class="b_ico" onclick="CommonDialog.close('feedCatalogDialog')"></a>
        <div class="b_con_03 clearfix">
        	<b class="page"><?php echo $currentCount;?>/<?php echo $maxCount; ?></b>
            <a href="#" class="next_page_left" id="prePage"><span><?php echo $page.'/'.$maxPage;?></span></a>
            <a href="#" class="next_page" id="nextPage"><?php echo min($page+1,$maxPage).'/'.$maxPage;?></a>
            <ul class="b_list_05 fl">
                 <?php 
                    $count = $index = 0 ;
                    $dressAttributes = array();
                    foreach($dataList as $dressId=>$data) {
                        $index ++ ;
                        if( $index<=($page-1)*$pageSize ) continue ;
                        if( $index>($page+1)*$pageSize ) break ;
                        $dressAttributes[$dressId] = $data->getAttributeSize();
                        echo '<li><span class="img01"><img src="'.$data->getImage().'" style="width:75px; cursor:pointer;" onclick="setFeedEquipData('.$dressId.',this.src)"></span>';
                        echo '<span class="tx01"><b>'.$data->getName().'</b>';
                        foreach( $data->getAttributeSize() as $k=>$v ) {
                            if( empty($v) ) continue ;
                            for( $j=0;$j<$v;$j++ ) {
                                echo '<img src="themes/images/imgb/ico_'.$k.'.png" alt="">';
                            }
                        }
                        echo '</span></li>';
                        $count ++ ;
                        if( $count%$pageSize==0 ) {
                            echo '</ul> <ul class="b_list_05 fl">';
                        }
                    }
                if( $currentCount<$maxCount&&$count<$pageSize*2 ) {
                 ?>
                 <li style="background:none;">
                    <span class="img01"><img src="themes/images/imgb/pic_23.png" alt=""></span>
                    <span class="tx02"><?php echo Yii::t('Catalog','breed dialog desc');?> </span>
                </li>
                <?php } ?>
            </ul>
        </div>
</div>	
<script>
var currentPage = <?php echo $page ; ?> ;
var maxPage = <?php echo $maxPage ; ?>;
$(document).ready(function(){
    if( currentPage>1 ) {
        $('#prePage').removeClass('op03').click(function(){
            showCatalogDialog(Math.max(currentPage-2,1));
        });
    }
    else {
        $('#prePage').addClass('op03') ;
    }
    if( currentPage+1<maxPage ) {
        $('#nextPage').removeClass('op03').click(function(){
            showCatalogDialog( Math.min(currentPage+2,maxPage));
        });
    }
    else {
        $('#nextPage').addClass('op03') ;
    }
}) ;


var dressAttributes = <?php echo json_encode($dressAttributes);?>;
var setFeedEquipData = function( id,src ){
    setDressData(dressAttributes[id]);
    $('#equipImage').attr('src',src);
    CommonDialog.close('feedCatalogDialog') ;
    window.selectedDressId = id ;
}
</script>