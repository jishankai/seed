<div class="frame frame_02">
<?php if (!$inGuide) {?>
    <p class="a_btn05 a_btnfan" style="position:absolute;top:65px;left:10px;"><a href="#" style="position:static;" class="b_ico" onclick="<?php echo $this->actionType==REQUEST_TYPE_NORMAL?'return false;':'closeInfoDialog()'; ?>"></a></p>
    <p class="a_btn05" style="position:absolute;top:66px;right:14px;"><a href="native://close" class="a_btn04" style="top:0;right:10px;"></a></p>
<?php }?>
    <p class="a_bar1bg" style="height:45px; text-align:center;"><span class="a_bar1" style="position:relative;left:0;"><?php echo Yii::t('Mission', $category)?></span></p>
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
echo 'SeedUnit("seed'.$data[1].'",'.json_encode(array($seedData->getDisplayData())).',0.54);';
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
            <em style="overflow:hidden"><div id="seed<?php echo $seedDetail[0]?>" style="margin-top:5px; margin-left:12px; width:2px"></div></em>
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

<?php
    if ($category=='random') {
        //计算剩余可放弃次数
        $command = Yii::app()->db->createCommand('SELECT COUNT(*) FROM missionRecord WHERE playerId = :playerId AND statusTime >= :today AND missionId >= :missionId AND status = :status GROUP BY playerId');
        $bindValue = array(
            ':playerId' => $playerId,
            ':missionId' => MISSION_SEEDRANDOMID,
            ':status' => MISSIONRECORD_CANCEL,
            ':today' => strtotime(date('Y-m-d 04:00:00')), 
        );
        $missionCount = $command->bindValues($bindValue)->queryScalar();
        $remainTimes = MISSION_RANDOM_MAX-$missionCount;
?>
    <p class="red fangqi"><?php echo str_replace('{X}', '<span>'.$remainTimes.'</span>', Yii::t('Mission', 'times that can be given up'))?></p>
<div class="b_btnlist01">
<?php
if ($remainTimes) {
?>
    <a href="#" class="b_btn_08"><?php echo Yii::t('Mission', 'cancel')?></a> 
<?php
} else {
?>
    <a href="#" class="b_btn_07" onclick="closeInfoDialog()"><?php echo Yii::t('Mission', 'ok')?></a> 
<?php
}
?>
<?php
    } else {
?>
<div class="b_btnlist01">
    <a href="#" class="b_btn_07" onclick="closeInfoDialog()"><?php echo Yii::t('Mission', 'ok')?></a> 
<?php 
    }
?>
</div>
</div>
<div style="clear:both; width:100%;height:20px;"></div>

<script>
    $('a.b_btn_08').click(function(){
        NativeApi.delay(true);
        ajaxLoader.get('<?php echo $this->createUrl('mission/cancel', array('missionId'=>$mission->missionId))?>', function(data){
            if (data.missionCount==0) {
                NativeApi.close().doRequest();
            } else {
                Common.refreshCurrentPage();
                NativeApi.doRequest();
            }
});
    });

    var closeInfoDialog = function(){
        if (<?php echo $this->actionType==REQUEST_TYPE_AJAX?1:0?>) {
            CommonDialog.close('InfoDialog');
            $('#<?php echo $mission->missionId?>').removeClass('green').addClass('blue');
        } else {
            <?php if($mission->missionId==2) { //新手引导任务 ?>
            $('#page').empty();
            NativeApi.callback({'userguide':'1','accessLevel':'40','guideMission':'0','close':'close'});
            <?php }else{ ?>
            $('#page').empty();
            NativeApi.close();
            <?php } ?>
        }
    }

    LoadAction.push(function(){
       <?php echo $this->actionType==REQUEST_TYPE_NORMAL?"$('a.a_btn04').unbind('click');":"";?>
    });
</script>
