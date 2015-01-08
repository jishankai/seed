<!--#include file="doctype.shtml"-->
<div class="bg_001 clearfix">
    <div class="this_header">
        <div class="b_ico" onclick="NativeApi.close();"></div>
        <div class="this_nav this_nav_cj">
            <h2><?php echo Yii::t('Achievement', 'title')?></h2>
            <h3>
                <?php echo Yii::t('Achievement', 'total')?>
                <span class="fr"><?php echo $completed?>/<?php echo $total?></span>
            </h3>
            <h3>
                <?php echo Yii::t('Achievement', 'percent')?>
                <span class="fr"><?php echo floor($completed*100/$total)?>%</span>
            </h3>
            <ul class="clearfix">
                <li <?php if($category==ACHIEVEMENT_CATE_COLLECT) echo 'class="current"'?> onclick="refreshAchieve('<?php echo $this->createUrl('achieve/collectList')?>')"></li>
                <li <?php if($category==ACHIEVEMENT_CATE_CONSUME) echo 'class="current"'?> onclick="refreshAchieve('<?php echo $this->createUrl('achieve/consumeList')?>')"></li>
                <li <?php if($category==ACHIEVEMENT_CATE_TASK) echo 'class="current"'?> onclick="refreshAchieve('<?php echo $this->createUrl('achieve/taskList')?>')"></li>
                <li <?php if($category==ACHIEVEMENT_CATE_OTHER) echo 'class="current"'?> onclick="refreshAchieve('<?php echo $this->createUrl('achieve/otherList')?>')"></li>
            </ul>
        </div>
    </div>
    <div class="this_top"></div>
    <div id="wrapper" style="height:485px">
<?php
if($achievementList != null){
?>
            <ul class="list_01">
<?php
    ksort($achievementList);
    foreach($achievementList as $status=>$achieveCateList){
        switch ($status) {
        case ACHIEVEMENTRECORD_COMPLETED:
            foreach ($achieveCateList as $achievement) {
?>
                <li>
                <div class="this_bar"><div style="width:100%;"></div></div>
                <span class="this_icon_01_<?php echo $category+1?>"></span>
                <!--
                <span class="this_icon_02"></span>
                <span class="this_icon_03"></span>
                <span class="this_icon_04"></span>
                -->
                <dl>
                    <dt><span><?php echo $achievement->paramsCount?>/<?php echo $achievement->paramsCount?></span><?php echo Yii::t('Achievement', $achievement->title)?></dt>
                    <dd>
                    <?php echo Yii::t('Achievement', $achievement->description)?>
                    </dd>
                </dl>
                <div class="this_right">
                    <h2 class="this_bg"><?php echo Yii::t('Achievement', 'rewards sent by mail')?></h2>
                    <div class="this_img">
<?php if(!empty($achievement->rewardItem)) {
    $itemRewards = explode(';', $achievement->rewardItem);
    foreach ($itemRewards as $itemReward) {
        $itemDetail = explode('_', $itemReward);
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemDetail[0]);
?>
                        <div class="this_img_01"><img src="<?php echo $itemMeta->getImagePath()?>" alt="" /><span><?php echo $itemDetail[1]?></span></div>
                        <?php }}?>
<?php if(!empty($achievement->rewardCup)) {
    $itemDetail = explode('_', $achievement->rewardCup);
    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemDetail[0]);
?>
                        <div class="this_img_01"><img src="<?php echo $itemMeta->getImagePath()?>" alt="" /></div>
                        <?php }?>
                        <?php if(!empty($achievement->rewardGold)) {?>
                        <div class="this_img_02"><div class="dib w40"><span class="cao">+<?php echo $achievement->rewardGold?></span></div></div>
                        <?php }?>
                        <?php if(!empty($achievement->rewardUserMoney)) {?>
                        <div class="this_img_02"><div class="dib w40"><span class="a_bg01 coffee">+<?php echo $achievement->rewardUserMoney?></span></div></div>
                        <?php }?>
                        <?php if(!empty($achievement->rewardExp)) {?>
                        <div class="this_img_03"><div class="dib w40"><span class="exp">+<?php echo $achievement->rewardExp?></span></div></div>
                        <?php }?>
                    </div>
                </div>
                </li>
<?php }
break;
case ACHIEVEMENTRECORD_UNCOMPLETED:
    foreach ($achieveCateList as $achievement) {
?>
                <li>
                <div class="this_bar"><div style="width:<?php echo floor(100*(empty($process[$achievement->achievementId])?0:$process[$achievement->achievementId])/$achievement->paramsCount)?>%;"></div></div>
                <span class="this_icon_01_<?php echo $category+1?>"></span>
                <!--
                <span class="this_icon_02"></span>
                <span class="this_icon_03"></span>
                <span class="this_icon_04"></span>
                -->
                <dl>
                    <dt><span><?php echo empty($process[$achievement->achievementId])?0:$process[$achievement->achievementId]?>/<?php echo $achievement->paramsCount?></span><?php echo Yii::t('Achievement', $achievement->title)?></dt>
                    <dd>
                    <?php echo Yii::t('Achievement', $achievement->description)?>
                    </dd>
                </dl>
                <div class="this_right">
                    <h2><?php echo Yii::t('Achievement', 'reward')?></h2>
                    <div class="this_img">
<?php if(!empty($achievement->rewardItem)) {
    $itemRewards = explode(';', $achievement->rewardItem);
    foreach ($itemRewards as $itemReward) {
        $itemDetail = explode('_', $itemReward);
        $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemDetail[0]);
?>
                        <div class="this_img_01"><img src="<?php echo $itemMeta->getImagePath()?>" alt="" /><span><?php echo $itemDetail[1]?></span></div>
                        <?php }}?>
<?php if(!empty($achievement->rewardCup)) {
    $itemDetail = explode('_', $achievement->rewardCup);
    $itemMeta = Yii::app()->objectLoader->load('ItemMeta', $itemDetail[0]);
?>
                        <div class="this_img_01"><img src="<?php echo $itemMeta->getImagePath()?>" alt="" /></div>
                        <?php }?>
                        <?php if(!empty($achievement->rewardGold)) {?>
                        <div class="this_img_02"><div class="dib w40"><span class="cao">+<?php echo $achievement->rewardGold?></span></div></div>
                        <?php }?>
                        <?php if(!empty($achievement->rewardUserMoney)) {?>
                        <div class="this_img_02"><div class="dib w40"><span class="a_bg01 coffee">+<?php echo $achievement->rewardUserMoney?></span></div></div>
                        <?php }?>
                        <?php if(!empty($achievement->rewardExp)) {?>
                        <div class="this_img_03"><div class="dib w40"><span class="exp">+<?php echo $achievement->rewardExp?></span></div></div>
                        <?php }?>
                    </div>
                </div>
                </li>
<?php }
break;
default:
    break;
        }

    }
}
?>
        </ul>
    </div>
</div>
<script language="javascript">
var refreshAchieve = function(url){
    ajaxLoader.get(url,function(data){
        $(".container").html(data.html);
        myScroll = new iScroll("wrapper",{hScrollbar:false, vScrollbar:false});
    });
}
var myScroll;
function loaded() {
    myScroll = new iScroll("wrapper",{hScrollbar:false, vScrollbar:false});
}
document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
var scrollDemo = document.getElementById("scrollDemo");
var scrollList = document.getElementsByTagName("ul");
scrollDemo.style.height = scrollList.length*260+100+"px";
</script>

