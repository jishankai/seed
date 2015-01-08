<div class="frame b_frame a_frame01 a_table ">
    <a href="native://close" class="a_btn04"></a>
    <p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('Mission', 'mission')?></span></p>
    <div class="a_table01">
		<div class="a_frame09">
    <?php
    if($missionList != null){
        ksort($missionList);
        foreach($missionList as $status=>$missions){
            switch ($status) {
            case MISSIONRECORD_COMPLETED:
            foreach ($missions as $mission) {
    ?>
	    <div id="<?php echo $mission->missionId?>" class="a_button01 blue" onclick="showInfoDialog(<?php echo $mission->missionId?>)"><?php echo Yii::t('Mission', $mission->title)?><span><?php if(in_array($mission->event, array(21,22))) {
    $seedData = Yii::app()->objectLoader->load('SeedData', $mission->expectedParams);
    echo str_replace("{SEED_DATA_NAME}", $seedData->getName(), Yii::t('Mission', $mission->description));
        } else if($mission->event==13) {
    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mission->expectedParams);
    echo str_replace("{DECO_NAME}", $itemMeta->getName(), Yii::t('Mission', $mission->description));
    } else {
        echo Yii::t('Mission', $mission->description);
    }?></span><a href="#" onclick="showInfoDialog(<?php echo $mission->missionId?>)"></a><i class="b_ico_com"></i></div>
    <?php }
            break;
            case MISSIONRECORD_UNCOMPLETED:
            foreach ($missions as $mission) {
    ?>
        <div id="<?php echo $mission->missionId?>" class="a_button01 blue" onclick="showInfoDialog(<?php echo $mission->missionId?>)"><?php echo Yii::t('Mission', $mission->title)?><span><?php if(in_array($mission->event, array(21,22))) {
    $seedData = Yii::app()->objectLoader->load('SeedData', $mission->expectedParams);
    echo str_replace("{SEED_DATA_NAME}", $seedData->getName(), Yii::t('Mission', $mission->description));
        } else if($mission->event==13) {
    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mission->expectedParams);
    echo str_replace("{DECO_NAME}", $itemMeta->getName(), Yii::t('Mission', $mission->description));
    } else {
        echo Yii::t('Mission', $mission->description);
    }?></span><a href="#" onclick="showInfoDialog(<?php echo $mission->missionId?>)"></a></div>
    <?php }
            break;
            case MISSIONRECORD_NEW:
            foreach ($missions as $mission) {
    ?>
        <div id="<?php echo $mission->missionId?>" class="a_button01 blue" onclick="showInfoDialog(<?php echo $mission->missionId?>)"><?php echo Yii::t('Mission', $mission->title)?><span><?php if(in_array($mission->event, array(21,22))) {
    $seedData = Yii::app()->objectLoader->load('SeedData', $mission->expectedParams);
    echo str_replace("{SEED_DATA_NAME}", $seedData->getName(), Yii::t('Mission', $mission->description));
        } else if($mission->event==13) {
    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mission->expectedParams);
    echo str_replace("{DECO_NAME}", $itemMeta->getName(), Yii::t('Mission', $mission->description));
    } else {
        echo Yii::t('Mission', $mission->description);
    }?></span><a href="#" onclick="showInfoDialog(<?php echo $mission->missionId?>)"></a><i class="b_ico_new"></i></div>
    <?php }
            break;
            default:
            break;
            }

            }
            } else{
    ?>
    <div class="a_button01 blue">
        <span><?php echo Yii::t('Mission', 'there is no mission')?></span>
        <a href=""></a>
    </div>
    <?php
        }
?>
    </div>
</div>
</div>
    <script language="javascript">
    var showInfoDialog = function(id){
        $('#'+id).removeClass('blue').addClass('green');
        $('#'+id+' .b_ico_new').remove();
        ajaxLoader.get('<?php echo $this->createUrl('mission/info')?>'+'&missionId='+id,function(data){ CommonDialog.create('InfoDialog',data); });
    }
    </script>

<?php if ($isGuide) {?>
<script>
//新手引导
var MissionGuide = {
    step: 0,
    zIndex: 201,
    nextStep: function(){
        this.step++;
        switch (this.step) {
            case 1:
                $('#missionMessage').html('<?php echo Yii::t('GuideMessage', 'missionmessage_1')?>');
                $('.b_text06').click(function(){
                    MissionGuide.nextStep();
                }); 
                break;
            case 2:
                $('.b_text06').unbind("click");
                $('.b_text05goon').hide();
                $('#missionMessage').html('<?php echo Yii::t('GuideMessage', 'missionmessage_2')?>');
                $('.a_button01').append('<div id="star" style="position:absolute; z-index:203; width:104%"></div>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('.a_button01').append('<em class="fangxiang new_left" style="position:absolute; z-index:203"></em>');
                $('.a_button01').append('<em class="new_hand" style="position:absolute; z-index:203"><span class="new_hand1"></span><span class="new_hand2"></span></em>');
                $('#star').css({top:14, left:-6});
                $('.fangxiang').css({top:18, left:468});
                $('.new_hand').css({top:26, left:200});
                $('.a_button01').attr('onclick', '').unbind('click').click(function(){
                    $('#star').remove();
                    $('.fangxiang').remove();
                    $('.new_hand').remove();
                    Common.goUrl("<?php echo $this->createUrl('mission/info', array('missionId'=>$mission->missionId))?>");
                });

            default:
                // code...
                break;
        }
    },
}

$(document).ready(function(){
    $('.b_frame05').hide();
    MissionGuide.nextStep();
});
LoadAction.push(function(){
    $('div.guide_main_cover').show().css('z-index',200);
});
</script>

<div style="width:216px; height:533px; overflow:hidden; position:absolute; bottom:0px;left:0px; z-index:201">
<div class="b_text06" style="float:right">
        <i class="text06pic" style="left:50px;"></i>
        <div class="height">
            <div id="text">
            <p id="missionMessage"></p>
            </div>
            <em class="b_text05goon"><span class="b_text05gnp"></span><span class="b_text05gnp1"></span></em>
		</div>
	</div>
</div>
<div style="height: 61px;clear: both;"></div>
<?php
}
?>
