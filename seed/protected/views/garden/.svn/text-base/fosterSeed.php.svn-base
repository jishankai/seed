<script language="javascript">
selectLittleGardenList.isNative = 1 ;
window.doFosterSeed = function(){
    var seedId = selectLittleGardenList.selectId ;

    var gardenId = <?php echo $gardenId ;?>;
    ajaxLoader.get('<?php echo $this->createUrl('seed/foster');?>&seedId='+seedId+'&gardenId='+gardenId,function(data){
        selectLittleGardenList.close();
    });
    
}

$(document).ready(function(){
    selectLittleGardenList.url='<?php echo $this->createUrl('Garden/LittleList',array('growPeriod'=>2)); ?>';
    selectLittleGardenList.showSeed( 'doFosterSeed()','window' );
});
</script>