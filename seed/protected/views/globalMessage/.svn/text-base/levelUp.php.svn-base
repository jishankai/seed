<style>
#bodyDataArea {
    width:82px; height:105px; margin-left:3px; margin-top:-20px;; overflow:hidden;
}
#faceDataArea {
    width:82px; height:105px; margin-left:3px; margin-top:-20px;; overflow:hidden;
}
#budDataArea {
    width:82px;  height:65px; padding-top:20px; margin-left:3px; overflow:hidden;
}
#dressDataArea {
    width:85px; height:85px; text-align:center; overflow:hidden;
}
#dressDataArea img {
    width:75px; margin:5px; float:left; 
}
.levelSeedContainer {
    width:55px; height:65px; float:left; margin-top:50px; margin-left:25px;
}
</style>



<div class="frame b_frame02 a_frame02" style="margin-top: -40px;">
    <span class="a_bar1 a_bar1_01" style="top:100px;"></span>
    <h2 class="f26 "><?php echo Yii::t('Player','level up title',array('{level}'=>$player->level));?></h2>
    <div class="a_frame02ac">
        <div class="a_frame02bg1">
            <div class="b_con_10 a_con_04 clearfix a_pt10 a_w464">				
                <?php 
                    foreach( $levelData['parts'] as $key=>$data ) {  
                        if( empty($data) ) continue ;
                        if( $key=='dress' ) {
                            $js = '$("#'.$key.'DataArea").html(\'<img src="'.$data->getImage().'" />\');';
                        }
                        else {
                            $js = 'new SeedUnit("'.$key.'DataArea",'.json_encode( array($data->getDisplayData()) ).',0.6)';
                        }
                        echo '<div class="a_bg02 a_w91"><div id="'.$key.'DataArea"></div><em></em></div>
                        <script language="javascript">$(function(){ '.$js.'; })</script>';
                    }
                 ?>
            </div>
            <div class="b_con_10 a_con_04 clearfix">
                <div class="a_bg02 a_w430">
                <?php foreach ($levelData['seeds'] as $i=>$s) { echo '<div class="levelSeedContainer" id="levelSeedContainer'.$i.'"></div>'; }?>
                </div>
            </div>
        </div>
        <?php
            if( $levelData['newGarden']||$levelData['newMap'] ) echo '<div class="a_frame02bg2"><div>';
            if( $levelData['newGarden'] ) {
                echo '<i class="a_grande">+1</i>';
            }
            if( $levelData['newMap'] ) {
                echo '<i class="a_earth">+1</i>';
            }
            if( $levelData['newGarden']||$levelData['newMap'] ) echo '</div></div>';
        ?>
    </div>
    <?php if($player->level <= 7){ ?>
    <div class="b_btnlist01 long">
        <a href="#" class="b_btn_07" onclick="CommonDialog.close('playerLevelUpDialog');">OK</a>
    </div>
    <?php }else{ ?>
    <div class="b_btnlist01 long"><a href="<?php echo $this->createUrl('Share/MessageShow'); ?>" class="b_btn_07 a_btn_blue a_btn_01"><?php echo Yii::t('View','share button');?></a><a href="#" class="b_btn_07 a_btn_02" onclick="CommonDialog.close('playerLevelUpDialog');">OK</a></div>
    <?php } ?>
</div>
<script>
<?php foreach( $levelData['seeds'] as $i=>$s ) {?>
new SeedUnit('levelSeedContainer<?php echo $i;?>',<?php echo SeedData::getAllDisplayData($s['bodyId'],$s['budId'],$s['faceId'],$s['dressId']);?>,0.45);
<?php } ?>
</script>


