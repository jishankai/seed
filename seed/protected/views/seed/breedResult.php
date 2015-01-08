
<div class="frame b_frame02 a_frame01">
    <a href="#" class="a_btn04" onclick="CommonDialog.close('breedResultDialog');"></a>
    <p class="a_bar1bg"><span class="a_bar1"><?php echo Yii::t('View','window prompt');?></span></p>
    <h2 class="f26 color_green "><?php echo Yii::t('Seed','breed success');?> </h2>
    <p class="f20 bold"><?php echo $newSeed->getName(); ?></p>
    <div class="b_con_10 a_con_02 clearfix mt-10 fz_con">
        <p><span class="exp color_green">+<?php echo $getExp; ?></span><?php echo $isFriend&&$spendMoney>0?'<span class="zz color_red">-'.$spendMoney.'</span>':''; ?></p>
        <p><span class="heart color_green">-2</span><span class="yz color_red">-<?php echo $spendGold; ?></span></p>
        <h4><?php echo Yii::t('Seed','first garden remain space');?> <?php echo $spaceSize; ?></h4>
        <h4><?php echo $isFriend?Yii::t('Seed','breed seed lock time').' 30min':Yii::t('Seed','breed seed continue');?></h4> 
        <div class="fzcom_text">
            <i><?php echo $isFriend?Yii::t('Seed','use money to continue',array('{price}'=>ShopModel::getBreedCDGoods()->price)):''?></i>
        </div>
    </div>
    <div class="b_btnlist01">
    <a href="#" class="b_btn_08 a_btn_03" onclick="setBreedMessage();CommonDialog.close('breedResultDialog');">NO</a>
    <a href="#" class="b_btn_07 a_btn_01" onclick="$('#breedResultDialog').remove();breedAction(<?php echo $isFriend?1:0;?>);"">YES</a></div>
</div>

