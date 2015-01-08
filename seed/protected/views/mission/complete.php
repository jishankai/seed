<div class="frame frame_02">
<?php if (!$inGuide) {?>
    <p class="a_btn05 a_btnfan" style="position:absolute;top:65px;left:10px;"><a href="#" style="position:static;" class="b_ico" onclick="<?php echo $this->actionType==REQUEST_TYPE_NORMAL?'return false;':'closeInfoDialog()'; ?>"></a></p>
    <p class="a_btn05" style="position:absolute;top:66px;right:14px;"><a href="native://close" class="a_btn04" style="top:0;right:10px;"></a></p>
<?php }?>
    <div class="b_text04"><?php echo Yii::t('Mission', 'complete')?></div>
    <h2 class="f26"><?php echo Yii::t('Mission', $mission->title)?></h2>
<?php if(in_array($mission->event, array(21,22))) {
    $seedData = Yii::app()->objectLoader->load('SeedData', $mission->expectedParams);
?>
    <p class="f18 mt10 ac"><?php echo str_replace("{SEED_DATA_NAME}", $seedData->getName(), Yii::t('Mission', $mission->description))?></p>
    <?php } else if($mission->event==13) {
    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mission->expectedParams);
?>
    <p class="f18 mt10 ac"><?php echo str_replace("{DECO_NAME}", $itemMeta->getName(), Yii::t('Mission', $mission->description))?></p>
    <?php } else {?>
    <p class="f18 mt10 ac"><?php echo Yii::t('Mission', $mission->description)?></p>
    <?php }?>
    <div class="b_con_10 clearfix">
<?php
if ($mission->missionId!=MISSION_TUTORIALID && $mission->missionId!=MISSION_SEEDRANDOMID) {
?>
        <div class="tx01 clearfix">
<?php if(!empty($mission->endImage)) {?>
<samp>
<?php
    $data = explode(',', $mission->endImage);
    switch ($data[0]) {
        case 'item':
            $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $data[1]);
            echo '<img style="width:82px; height:82px" src="'.$itemMeta->getImagePath().'" alt="">';
            break;
        case 'seed':
            $seedData = Yii::app()->objectLoader->load("SeedData", $data[1]);
            echo '<div id="seed'.$data[1].'" class="seedData'.$seedData->dataType.'" style="padding-left:10px; padding-top:5px"></div>';
?>
<script>
<?php
$seedData = Yii::app()->objectLoader->load('SeedData',$data[1]);
echo 'SeedUnit("seed'.$data[1].'",'.json_encode(array($seedData->getDisplayData())).',0.55);';
?>
</script>
<style>
.b_con_10 .tx01 .seedData1 {
    margin-left:2px; margin-top:-5px;
}
.b_con_10 .tx01 .seedData2 {
    margin-left:2px; margin-top:0px;
}
.b_con_10 .tx01 .seedData3 {
    margin-left:2px; margin-top:35px;
}
</style>
<?php
            break;
        
        default:
            break;
    }
?>
</samp>
<div class="middle">
<?php
} else {
?>
<div class="middle empty">
<?php
}
?>
<?php 
if (Yii::t('Mission', $mission->endPre)!=' ') {
    echo Yii::t('Mission', $mission->endPre);
} else if(in_array($mission->event, array(21,22))) {
    $seedData = Yii::app()->objectLoader->load('SeedData', $mission->expectedParams);
    echo $seedData->getName();
} else if ($mission->event==13) {
    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $mission->expectedParams);
    echo $itemMeta->getName();
}
?>
            <span class="balck">
<?php
if (!empty($mission->endCount)) {
    echo $mission->endCount.'/'.$mission->endCount;
}
?>
            </span>
            <b>
<?php
if (!empty($mission->endNext)) {
    echo Yii::t('Mission', $mission->endNext);
}
?>
            </b>
</div>
        </div>
<?php
} else if ($mission->missionId==MISSION_SEEDRANDOMID) {
?>
        <div class="tx01 clearfix">
            <samp>
<?php 
    echo '<div id="seedRandom" style="margin-left:18px; margin-top:18px"></div>';
?>
<script>
<?php
    $id = MissionRecord::findId($playerId, $mission->missionId);
    $missionRecord = Yii::app()->objectLoader->load('MissionRecord', $id);
    $data = explode(',', $missionRecord->process);
    $bodyId = $data[0];
    $faceId = $data[1];
    $budId = $data[2];
    $displayData = SeedData::getAllDisplayData($bodyId, $faceId, $budId);
    echo 'SeedUnit("seedRandom",'.$displayData.',0.50);';
?>
</script>
</samp>
<div class="middle">
<?php 
if (!empty($mission->endPre)) {
    $name = SeedData::getAllName($bodyId, $faceId, $budId);
    echo Yii::t('Mission', $mission->endPre).$name;
}
?>
            <span class="balck">
<?php
if (!empty($mission->endCount)) {
    echo $process.'/'.$mission->endCount;
}
?>
            </span>
            <b>
<?php
if (!empty($mission->endNext)) {
    echo Yii::t('Mission', $mission->endNext);
}
?>
            </b>
</div>
        </div>
<?php
}
?>
        <div class="tx02">
            <span class="reward"><?php echo Yii::t('Mission', 'reward')?></span>
<?php if(!empty($mission->rewardSeed)) {
    $seedInfo = explode('_', $mission->rewardSeed);
    $seedDetail = explode(':', $seedInfo[0]);
?>
            <em><div id="seed<?php echo $seedDetail[0]?>" style="width:49px; height:52px; padding-top:5px; padding-left:20px"></div></em>
    <script>
<?php
    if (!empty($seedDetail[1]) && !empty($seedDetail[2])) {
        echo 'SeedUnit("seed'.$seedDetail[0].'",'.SeedData::getAllDisplayData($seedDetail[0], $seedDetail[1], $seedDetail[2]).',0.42);';
    } else {
        echo 'SeedUnit("seed'.$seedDetail[0].'",'.SeedData::getAllDisplayData($seedDetail[0]).',0.42);';
    }
?>
    </script>
            <?php }?>
<?php if(!empty($mission->rewardItem)) {
    $itemDetail = explode('_', $mission->rewardItem);
    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemDetail[0]);
?>
            <em><img style="width:49px; height:52px" src="<?php echo $itemMeta->getImagePath()?>" alt=""></em>
            <?php }?>
            <?php if(!empty($mission->rewardGold)) {?> 
            <em><img style="margin-bottom: -35px;" src="themes/images/imgb/pic_08.png" alt=""><br><?php echo $mission->rewardGold?></em>
            <?php }?>
            <?php if(!empty($mission->rewardUserMoney)) {?> 
            <em class="b_gold"><img style="margin-bottom: -35px;" src="themes/images/imga/a_ico51.png" alt=""><br><?php echo $mission->rewardUserMoney?></em>
            <?php }?>
            <?php if(!empty($mission->rewardExp)) {?>
            <em><img style="margin-bottom:-35px;" src="themes/images/imgb/pic_40.png" alt=""><br><?php echo $mission->rewardExp?></em>
            <?php }?>
        </div>
    </div>
<div class="b_btnlist01">
    <a href="#" class="b_btn_07"><?php echo Yii::t('Mission', 'ok')?></a>
</div>
</div>

<script>
    $('a.b_btn_07').click(function(){
        if (!isClicked) {
            isClicked = !isClicked;
            if (<?php echo ($mission->missionId<10&&$mission->missionId>1)?1:0?>) {
                NativeApi.delay(true);
                ajaxLoader.get('<?php echo $this->createUrl('mission/torturalConfirm', array('missionId'=>$mission->missionId))?>', function(data){
                    NativeApi.doRequest();
                    Common.goUrl("<?php echo $this->createUrl('mission/info')?>&missionId="+data.missionId);
                    $('#page').empty();
                });
            } else {
                NativeApi.delay(true);
                ajaxLoader.get('<?php echo $this->createUrl('mission/confirm', array('missionId'=>$mission->missionId))?>', function(data){
                    if (data.missionCount==0) {
                        NativeApi.close().doRequest();
                    } else {
                        Common.refreshCurrentPage();
                        NativeApi.doRequest();
                    }
                });
            }
            setTimeout('isClicked=false', 10000);
        }
    });
    isClicked = false;
    var closeInfoDialog = function(){
        CommonDialog.close('InfoDialog');
        $('#<?php echo $mission->missionId?>').removeClass('green').addClass('blue');
    }

    LoadAction.push(function(){
       <?php echo $this->actionType==REQUEST_TYPE_NORMAL?"$('a.a_btn04').unbind('click');":"";?>
    });
</script>

<?php if ($isGuide) {?>
<script>
//新手引导
var isOver = false;
var MissionGuide = {
    step: 0,
    zIndex: 1002,
    nextStep: function(){
        this.step++;
        switch (this.step) {
            /*
            case 1:
                $('#missionMessage').html('<?php echo Yii::t('GuideMessage', 'missionmessage_3')?>');
                $('.b_text06').click(function(){
                    MissionGuide.nextStep();
                });                
                break;*/
            case 1:
                $('.b_text06').unbind("click");
                $('.b_text05goon').hide();
                $('#missionMessage').html('<?php echo Yii::t('GuideMessage', 'missionmessage_3')?>').fadeIn();
                $('.frame_02').append('<div id="star" style="position:absolute; z-index:1004"></div>');
                $('#star').append('<em class="star"></em>');
                $('#star').append('<em class="star"></em>');
                $('.frame_02').append('<em class="fangxiang new_left" style="position:absolute; z-index:1003"></em>');
                $('.frame_02').append('<em class="new_hand_03" style="position:absolute; z-index:1003"><span class="new_hand1"></span><span class="new_hand2"></span></em>');
                $('#star').css({top:448, left:210}).click(function(){
                    if (!isClicked) {
                        isClicked = !isClicked;
                        ajaxLoader.get('<?php echo $this->createUrl('mission/torturalConfirm', array('missionId'=>$mission->missionId))?>', function(data){
                        if (<?php echo $mission->missionId?>!=1) {
                            Common.goUrl("<?php echo $this->createUrl('mission/info')?>&missionId="+data.missionId);
                        } else {
                             if (!isOver) {
                                MissionGuide.over();
                                setTimeout("isOver = false", 2000);
                            }
                        }
                        
                        });  
                       
                    }
                    setTimeout('isClicked=false', 10000);
                });
                $('.fangxiang').css({top:450, left:420});
                isClicked = false;
                $('.new_hand_03').css({top:410, left:228})
            default:
                // code...
                break;
        }
    },
    over: function(){
        NativeApi.delay(true);
        ajaxLoader.get('<?php echo $this->createUrl('guide/saveStatus&accessLevel=29')?>', function(){
            NativeApi.close().doRequest();
        });
    }
}

$(document).ready(function(){
    $('.b_frame05').hide();
    MissionGuide.nextStep();
});
LoadAction.push(function(){
    $('div.guide_main_cover').show().css('z-index',1000);
});
</script>
<div style="width:216px; height:533px; overflow:hidden;bottom:0px;left:0px; position:absolute; z-index:1001">
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
} else {
?>
<div style="clear:both; width:100%;height:20px;"></div>
<?php } ?>
